<?php

namespace App\Http\Controllers\Home;

use App\Enums\StatusEnum;
use App\Models\BusLine;
use App\Models\Favorite;
use App\Models\Location;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Stop extends Component
{
    use SEOToolsTrait;

    public Location $stop;

    public array $nearbyStops = [];

    public array $lines = [];


    public bool $is_favorite = false;

    public function mount(Location $location): void
    {
        $this->stop = $location->load('city');

        $locale = app()->getLocale();
        $stopName = $this->stop->getTranslation('name', $locale) ?? __('words.unknown');

        $this->seo()->setTitle($stopName);
        $this->seo()->setDescription(__('words.seo_description', ['name' => $stopName]));
        $this->seo()->opengraph()->setUrl(route('words.show', $this->stop));
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->twitter()->setSite('@' . setting()->get('company_name', 'Rega'));

        $this->nearbyStops = $this->computeNearbyStops($this->stop);
        $this->lines = $this->loadLines($this->stop);
        $this->is_favorite = auth()->check()
            && Favorite::query()
                ->where('location_id', $this->stop->id)
                ->exists();
    }

    #[Layout('layouts.app')]
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('home.stop');
    }

    public function toggleFavorite(): void
    {
        $userId = auth()->id();
        if ( ! $userId) {
            return;
        }

        $existing = Favorite::query()
            ->where('user_id', $userId)
            ->where('location_id', $this->stop->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->is_favorite = false;
            return;
        }

        Favorite::create([
            'user_id' => $userId,
            'location_id' => $this->stop->id,
        ]);

        $this->is_favorite = true;
    }

    private function loadLines(Location $location): array
    {
        $locale = app()->getLocale();

        return BusLine::query()
            ->with(['fromLocation', 'toLocation'])
            ->where('status', StatusEnum::Active)
            ->where(function ($q) use ($location): void {
                $q->where('from_location_id', $location->id)
                    ->orWhere('to_location_id', $location->id);
            })
            ->latest()
            ->get()
            ->map(fn(BusLine $line): array => [
                'id' => $line->id,
                'from_name' => $line->fromLocation?->getTranslation('name', $locale) ?? __('words.unknown'),
                'to_name' => $line->toLocation?->getTranslation('name', $locale) ?? __('words.unknown'),
                'from_id' => $line->from_location_id,
                'to_id' => $line->to_location_id,
                'route_json_url' => $line->route_json_file,
            ])
            ->toArray();
    }

    private function computeNearbyStops(Location $location): array
    {
        $coords = is_array($location->map_location) ? $location->map_location : [];
        $lat = data_get($coords, 'lat');
        $lng = data_get($coords, 'lng');
        if ( ! is_numeric($lat) || ! is_numeric($lng)) {
            return [];
        }

        $lat = (float) $lat;
        $lng = (float) $lng;
        $locale = app()->getLocale();

        return DB::table('locations')
            ->where('city_id', $location->city_id)
            ->whereNotNull('map_location')
            ->where('id', '!=', $location->id)
            ->get()
            ->map(function ($other) use ($lat, $lng, $locale): array {
                $o = json_decode($other->map_location, true) ?? [];
                $olat = data_get($o, 'lat');
                $olng = data_get($o, 'lng');
                if ( ! is_numeric($olat) || ! is_numeric($olng)) {
                    return [];
                }

                // calculate distance in km
                $distanceKm = $this->haversineKm($lat, $lng, (float) $olat, (float) $olng);

                return [
                    'id' => $other->id,
                    'name' => $other->name ? json_decode($other->name, true)[$locale] ?? __('words.unknown') : __('words.unknown'),
                    'distance_km' => $distanceKm,
                ];
            })
            ->filter(fn(array $row): bool => isset($row['id']))
            ->sortBy('distance_km')
            ->take(8)
            ->values()
            ->toArray();
    }

    /**
     * this is Haversine formula to calculate distance between two lat/lon points in kilometers
     */
    private function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        // convert degrees to radians
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        // Haversine formula
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        // calculate the great circle distance
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // return distance in kilometers
        return $earthRadius * $c;
    }

}

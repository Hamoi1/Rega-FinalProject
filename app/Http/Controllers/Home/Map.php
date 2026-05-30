<?php

namespace App\Http\Controllers\Home;

use App\Enums\StatusEnum;
use App\Models\BusLine;
use App\Models\Favorite;
use App\Models\Location;
use App\Traits\WithNotification;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Map extends Component
{
    use SEOToolsTrait;
    use WithNotification;
    use WithPagination;

    #[Url(as: 'city')]
    public $city_id;

    #[Url(as: 'from')]
    public $route_from_id;

    #[Url(as: 'to')]
    public $route_to_id;

    public $allLocations = [];

    public $search = '';

    public array $favorite_line_ids = [];


    public function mount(): void
    {
        $this->seo()->setTitle(__('words.map'));
        $this->seo()->setDescription(__('words.home_description'));
        $this->seo()->opengraph()->setUrl(route('map'));
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->twitter()->setSite('@' . setting()->get('company_name', 'Rega'));
        if ($this->route_from_id && $this->route_to_id && $this->city_id) {
            $this->loadLineData(onMount: true);
        }

        if ($this->route_from_id && ( ! $this->route_to_id || ! $this->city_id)) {
            $this->loadFromLocation();
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $locale = app()->getLocale();
        $fromLocationNameSubquery = DB::table('locations as from_locations')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(from_locations.name, "$.' . $locale . '"))')
            ->whereColumn('from_locations.id', 'bus_lines.from_location_id')
            ->limit(1);

        $toLocationNameSubquery = DB::table('locations as to_locations')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(to_locations.name, "$.' . $locale . '"))')
            ->whereColumn('to_locations.id', 'bus_lines.to_location_id')
            ->limit(1);

        $buses_lines = DB::table('bus_lines')
            ->select('bus_lines.id')
            ->addSelect('bus_lines.from_location_id')
            ->selectSub($fromLocationNameSubquery, 'from_location_name')
            ->selectSub($toLocationNameSubquery, 'to_location_name')
            ->leftJoin('media as route_media', function ($join): void {
                $join->on('route_media.model_id', '=', 'bus_lines.id')
                    ->where('route_media.model_type', '=', BusLine::class)
                    ->where('route_media.collection_name', '=', 'route_json_file');
            })
            ->addSelect([
                'route_media.id as route_media_id',
                'route_media.file_name as route_media_file_name',
                'route_media.disk as route_media_disk',
                'route_media.uuid as route_media_uuid',
            ])
            ->whereIn('bus_lines.from_location_id', function ($query): void {
                $query->select('id')
                    ->from('locations')
                    ->where('city_id', $this->city_id);
            })
            ->when(
                $this->route_from_id,
                fn($query) => $query->where('bus_lines.from_location_id', $this->route_from_id),
            )
            ->when(
                $this->route_to_id,
                fn($query) => $query->where('bus_lines.to_location_id', $this->route_to_id),
            )
            ->when($this->search, function ($query) use ($locale): void {
                $searchTerm = '%' . mb_strtolower($this->search) . '%';
                $query->where(function ($query) use ($searchTerm, $locale): void {
                    $query->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT((SELECT name FROM locations WHERE locations.id = bus_lines.from_location_id LIMIT 1), "$.' . $locale . '"))) LIKE ?', [$searchTerm])
                        ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT((SELECT name FROM locations WHERE locations.id = bus_lines.to_location_id LIMIT 1), "$.' . $locale . '"))) LIKE ?', [$searchTerm]);
                });
            })
            ->where('bus_lines.status', StatusEnum::Active->value)
            ->paginate(20);

        $buses_lines->getCollection()->transform(static function ($line) {
            $line->route_json_file = null;
            if (null !== $line->route_media_id && null !== $line->route_media_file_name) {
                $filePath = sprintf('%s/%s', $line->route_media_id, $line->route_media_file_name);
                if (Storage::disk($line->route_media_disk)->exists($filePath)) {
                    $line->route_json_file = Storage::disk($line->route_media_disk)->url($filePath);
                }
            }

            return $line;
        });

        if (Auth::check()) {
            $this->favorite_line_ids = DB::table('favorites')
                ->where('user_id', auth()->id())
                ->whereIn('location_id', $buses_lines->getCollection()->pluck('from_location_id')->all())
                ->pluck('location_id')
                ->map(fn($id): int => (int) $id)
                ->values()
                ->all();
        } else {
            $this->favorite_line_ids = [];
        }

        $this->allLocations = $this->locationMarks();

        return view('home.map', [
            'buses_lines' => $buses_lines,
            'allLocations' => $this->allLocations,
        ]);
    }

    public function updatedCityId(): void
    {
        $this->reset(['route_from_id', 'route_to_id']);
        $this->dispatch('load-map-data', [
            'mapId' => 'public-map',
            'geoJsonUrl' => null,
            'routeStartName' => null,
            'routeEndName' => null,
            'marks' => $this->locationMarks(),
        ]);
    }

    public function updated($property, $value): void
    {
        if (in_array($property, ['route_from_id', 'route_to_id'])) {
            if ($this->route_from_id && $this->route_to_id) {
                // ئەگەر هەردووکی دیاری کرابوو، هێڵەکە بکێشە!
                $this->loadLineData(false);
            } else {
                $this->dispatch('load-map-data', [
                    'mapId' => 'public-map',
                    'geoJsonUrl' => null,
                    'routeStartName' => null,
                    'routeEndName' => null,
                    'marks' => $this->locationMarks(),
                ]);
            }
        }
    }

    public function clearAll(): void
    {
        $this->reset(['city_id', 'route_from_id', 'route_to_id', 'search']);
        $this->dispatch('load-map-data', [
            'mapId' => 'public-map',
            'geoJsonUrl' => null,
            'routeStartName' => null,
            'routeEndName' => null,
            'marks' => [],
        ]);
    }

    public function toggleFavoriteLine(int $locationId): void
    {
        $userId = auth()->id();
        if ( ! $userId) {
            $this->warning(__('messages.login_to_favorite_lines'));
            return;
        }

        if ($locationId <= 0) {
            return;
        }

        if ( ! Location::query()->whereKey($locationId)->exists()) {
            return;
        }

        $existing = Favorite::query()
            ->where('user_id', $userId)
            ->where('location_id', $locationId)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->favorite_line_ids = array_values(array_diff($this->favorite_line_ids, [$locationId]));
            return;
        }

        Favorite::create([
            'user_id' => $userId,
            'location_id' => $locationId,
        ]);

        $this->favorite_line_ids[] = $locationId;
        $this->favorite_line_ids = array_values(array_unique($this->favorite_line_ids));
    }

    private function locationMarks(): array
    {
        // ئەگەر هێڵێکی دیاریکراو هەڵبژێردرابێت، خاڵەکانی تر پیشان مەدە
        if ($this->route_from_id && $this->route_to_id) {
            return [];
        }

        $locale = app()->getLocale();

        return DB::table('locations')
            ->select('locations.id')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.' . $locale . '")) as name')
            ->selectRaw('JSON_EXTRACT(map_location, "$.lat") as lat')
            ->selectRaw('JSON_EXTRACT(map_location, "$.lng") as lng')
            ->whereNotNull('map_location')
            ->where('city_id', $this->city_id)
            ->when($this->search, function ($query) use ($locale): void {
                $searchTerm = '%' . mb_strtolower($this->search) . '%';
                $query->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.' . $locale . '"))) LIKE ?', [$searchTerm]);
            })
            ->when($this->route_from_id, function ($query): void {
                $query->whereIn('locations.id', function ($subQuery): void {
                    $subQuery->select('from_location_id')
                        ->from('bus_lines')
                        ->where('from_location_id', $this->route_from_id)
                        ->orWhere('to_location_id', $this->route_from_id)
                        ->union(
                            DB::table('bus_lines')
                                ->select('to_location_id')
                                ->where('from_location_id', $this->route_from_id)
                                ->orWhere('to_location_id', $this->route_from_id),
                        );
                });
            })
            ->distinct()
            ->get()
            ->map(static function ($location): array {
                $lat = isset($location->lat) ? (float) $location->lat : null;
                $lng = isset($location->lng) ? (float) $location->lng : null;

                return [
                    'id' => $location->id,
                    'lat' => $lat,
                    'lng' => $lng,
                    'name' => $location->name,
                    'type' => 'busStop',
                ];
            })
            ->filter(fn(array $mark): bool => null !== $mark['lat'] && null !== $mark['lng'])
            ->values()
            ->toArray();
    }

    private function loadLineData(bool $onMount = false): void
    {
        $locale = app()->getLocale();
        $bus_line = BusLine::query()
            ->withAggregate('fromLocation as from_location_name', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.' . $locale . '"))'))
            ->withAggregate('toLocation as to_location_name', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.' . $locale . '"))'))
            ->where('from_location_id', $this->route_from_id)
            ->where('to_location_id', $this->route_to_id)
            ->active()
            ->first();

        $this->dispatch(
            $onMount ? 'mount-map-data' : 'load-map-data',
            [
                'mapId' => 'public-map',
                'geoJsonUrl' => $bus_line?->route_json_file,
                'routeStartName' => $bus_line?->from_location_name ?? null,
                'routeEndName' => $bus_line?->to_location_name ?? null,
                'routeId' => $bus_line?->id ?? null,
                'marks' => [], // لێرەدا خاڵەکانی تر پاک دەکەینەوە
            ],
        );
    }

    private function loadFromLocation(): void
    {
        $locale = app()->getLocale();
        $fromLocation = Location::query()
            ->select(['id', 'city_id'])
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.' . $locale . '")) as name')
            ->whereKey($this->route_from_id)
            ->first();

        if ($fromLocation) {
            $this->city_id = $fromLocation->city_id;
            $this->dispatch('load-map-data', [
                'mapId' => 'public-map',
                'geoJsonUrl' => null,
                'routeStartName' => $fromLocation->name,
                'routeEndName' => null,
                'marks' => $this->locationMarks(),
            ]);
        }
    }
}

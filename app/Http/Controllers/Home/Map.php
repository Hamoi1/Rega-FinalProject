<?php

namespace App\Http\Controllers\Home;

use App\Enums\StatusEnum;
use App\Models\BusLine;
use App\Models\Favorite;
use App\Models\Location;
use App\Services\RouteFinder\RouteFinder;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Map extends Component
{
    use SEOToolsTrait;
    use WithPagination;

    #[Url(as: 'city')]
    public $city_id;

    #[Url(as: 'from')]
    public $route_from_id;

    #[Url(as: 'to')]
    public $route_to_id;

    public array $allLocations = [];
    public string $search = '';
    public array $favorite_line_ids = [];

    public array $multiRouteSegments = [];
    public ?string $multiRouteGeoJsonUrl = null;

    public function mount(): void
    {
        $this->seo()->setTitle(__('words.map'))
            ->setDescription(__('words.home_description'));
        $this->seo()->opengraph()->setUrl(route('map'))->addProperty('type', 'website');
        $this->seo()->twitter()->setSite('@' . setting()->get('company_name', 'Rega'));

        if ($this->route_from_id && $this->route_to_id && $this->city_id) {
            $this->loadLineData(onMount: true);
        } elseif ($this->route_from_id && ( ! $this->route_to_id || ! $this->city_id)) {
            $this->loadFromLocation();
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $locale = app()->getLocale();

        if ( ! empty($this->multiRouteSegments)) {
            $buses_lines = new LengthAwarePaginator([], 0, 20, 1);
            $this->favorite_line_ids = [];
        } else {
            $s = '%' . mb_strtolower($this->search) . '%';

            $buses_lines = clone DB::table('bus_lines as bl')
                ->join('locations as lf', 'lf.id', '=', 'bl.from_location_id')
                ->join('locations as lt', 'lt.id', '=', 'bl.to_location_id')
                ->leftJoin('media as m', fn($j) => $j->on('m.model_id', '=', 'bl.id')
                    ->where('m.model_type', BusLine::class)
                    ->where('m.collection_name', 'route_json_file'))
                ->where('bl.status', StatusEnum::Active->value)
                ->when($this->city_id, fn($q) => $q->where('lf.city_id', $this->city_id))
                ->when($this->route_from_id, fn($q) => $q->where('bl.from_location_id', $this->route_from_id))
                ->when($this->route_to_id, fn($q) => $q->where('bl.to_location_id', $this->route_to_id))
                ->when($this->search, fn($q) => $q->where(
                    fn($sub) => $sub
                        ->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(lf.name, '$.{$locale}'))) LIKE ?", [$s])
                        ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(lt.name, '$.{$locale}'))) LIKE ?", [$s]),
                ))
                ->select([
                    'bl.id', 'bl.from_location_id', 'bl.to_location_id',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(lf.name, '$.{$locale}')) as from_location_name"),
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(lt.name, '$.{$locale}')) as to_location_name"),
                    'm.id as m_id', 'm.disk as m_disk','m.file_name as m_file_name',
                ])
                ->paginate(20);

            $buses_lines->getCollection()->transform(static function ($line) {
                $line->route_json_file = null;
                if (null !== $line->m_id && null !== $line->m_file_name) {
                    $filePath = sprintf('%s/%s', $line->m_id, $line->m_file_name);
                    if (Storage::disk($line->m_disk)->exists($filePath)) {
                        $line->route_json_file = Storage::disk($line->m_disk)->url($filePath);
                    }
                }

                return $line;
            });

            $this->favorite_line_ids = Auth::check()
                ? Favorite::where('user_id', auth()->id())
                    ->whereIn('location_id', $buses_lines->pluck('from_location_id'))
                    ->pluck('location_id')->map(fn($id) => (int) $id)->all()
                : [];
        }

        $this->allLocations = $this->locationMarks();

        return view('home.map', compact('buses_lines'));
    }

    public function updated($property, $value): void
    {
        if ('city_id' === $property) {
            $this->reset(['route_from_id', 'route_to_id', 'multiRouteSegments', 'multiRouteGeoJsonUrl']);
            $this->dispatchMapData(false);
        } elseif (in_array($property, ['route_from_id', 'route_to_id'])) {
            if ($this->route_from_id && $this->route_to_id) {
                $this->loadLineData(false);
            } else {
                $this->reset(['multiRouteSegments', 'multiRouteGeoJsonUrl']);
                $this->dispatchMapData(false);
            }
        }
    }

    public function clearAll(): void
    {
        $this->reset(['city_id', 'route_from_id', 'route_to_id', 'search', 'multiRouteSegments', 'multiRouteGeoJsonUrl']);
        $this->dispatchMapData(false);
    }

    public function toggleFavoriteLine(int $locationId): void
    {
        if ( ! Auth::check()) {
            $this->warning(__('messages.login_to_favorite_lines'));
            return;
        }

        if ($locationId <= 0 || ! Location::whereKey($locationId)->exists()) {
            return;
        }

        $fav = Favorite::where('user_id', auth()->id())->where('location_id', $locationId)->first();
        $fav ? $fav->delete() : Favorite::create(['user_id' => auth()->id(), 'location_id' => $locationId]);

        $this->favorite_line_ids = Favorite::where('user_id', auth()->id())->pluck('location_id')->map(fn($id) => (int) $id)->all();
    }

    private function locationMarks(): array
    {
        if ($this->route_from_id && $this->route_to_id) {
            return [];
        }

        $locale = app()->getLocale();
        return DB::table('locations')
            ->where('city_id', $this->city_id)
            ->whereNotNull('map_location')
            ->when($this->search, fn($q) => $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}'))) LIKE ?", ['%' . mb_strtolower($this->search) . '%']))
            ->when($this->route_from_id, fn($q) => $q->whereIn('id', function ($sub): void {
                $sub->select('from_location_id')->from('bus_lines')->where('from_location_id', $this->route_from_id)->orWhere('to_location_id', $this->route_from_id)
                    ->union(DB::table('bus_lines')->select('to_location_id')->where('from_location_id', $this->route_from_id)->orWhere('to_location_id', $this->route_from_id));
            }))
            ->select(['id', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}')) as name"), DB::raw("JSON_EXTRACT(map_location, '$.lat') as lat"), DB::raw("JSON_EXTRACT(map_location, '$.lng') as lng")])
            ->distinct()->get()
            ->filter(fn($l) => is_numeric($l->lat) && is_numeric($l->lng))
            ->map(fn($l) => ['id' => $l->id, 'lat' => (float) $l->lat, 'lng' => (float) $l->lng, 'name' => $l->name, 'type' => 'busStop'])
            ->values()->all();
    }

    private function loadLineData(bool $onMount = false): void
    {
        $locale = app()->getLocale();
        $this->reset(['multiRouteSegments', 'multiRouteGeoJsonUrl']);

        // 1. Try Direct Route
        $direct = DB::table('bus_lines as bl')
            ->join('locations as lf', 'lf.id', '=', 'bl.from_location_id')
            ->join('locations as lt', 'lt.id', '=', 'bl.to_location_id')
            ->leftJoin('media as m', fn($j) => $j->on('m.model_id', '=', 'bl.id')->where('m.model_type', BusLine::class)->where('m.collection_name', 'route_json_file'))
            ->where('bl.from_location_id', $this->route_from_id)
            ->where('bl.to_location_id', $this->route_to_id)
            ->where('bl.status', StatusEnum::Active->value)
            ->select(['bl.id', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(lf.name, '$.{$locale}')) as f_name"), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(lt.name, '$.{$locale}')) as t_name"), 'm.id as m_id', 'm.file_name as m_name', 'm.disk as m_disk'])
            ->first();

        if ($direct) {
            $geoUrl = ($direct->m_id && $direct->m_name) ? Storage::disk($direct->m_disk ?? 'public')->url(sprintf('files/%s/%s', mb_substr(md5($direct->m_id . config('app.key')), 0, 8), $direct->m_name)) : null;
            $this->dispatchMapData($onMount, $geoUrl, $direct->f_name, $direct->t_name, $direct->id);
            return;
        }

        // 2. Try Multi-Segment Route
        if ( ! $this->city_id || ! ($res = app(RouteFinder::class)->find($this->route_from_id, $this->route_to_id, $this->city_id))) {
            $this->dispatchMapData($onMount);
            return;
        }

        $locNames = Location::whereIn('id', [$this->route_from_id, $this->route_to_id])
            ->select(['id', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}')) as name")])
            ->pluck('name', 'id')->all();

        $this->multiRouteSegments = $res->segments;
        $this->multiRouteGeoJsonUrl = 'data:application/json;base64,' . base64_encode(json_encode($res->geoJson));

        $this->dispatchMapData(
            $onMount,
            $this->multiRouteGeoJsonUrl,
            $locNames[$this->route_from_id] ?? __('words.unknown'),
            $locNames[$this->route_to_id] ?? __('words.unknown'),
        );
    }

    private function loadFromLocation(): void
    {
        $loc = Location::select(['city_id', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(name, '$." . app()->getLocale() . "')) as name")])
            ->whereKey($this->route_from_id)->first();

        if ($loc) {
            $this->city_id = $loc->city_id;
            $this->dispatchMapData(false, null, $loc->name);
        }
    }

    private function dispatchMapData(bool $onMount, ?string $geoJsonUrl = null, ?string $startName = null, ?string $endName = null, ?int $routeId = null): void
    {
        $this->dispatch($onMount ? 'mount-map-data' : 'load-map-data', [
            'mapId' => 'public-map',
            'geoJsonUrl' => $geoJsonUrl,
            'routeStartName' => $startName,
            'routeEndName' => $endName,
            'routeId' => $routeId,
            'marks' => $this->locationMarks(),
        ]);
    }
}

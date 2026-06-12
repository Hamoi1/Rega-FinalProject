<?php

namespace App\Services\RouteFinder;

use App\Enums\StatusEnum;
use App\Models\BusLine;
use Illuminate\Support\Facades\{Cache, DB, Storage};
use SplPriorityQueue;

class RouteFinder
{
    private const TRANSFER_PENALTY_KM = 0.05;

    public function find(int $from, int $to, ?int $cityId = null): ?RouteFinderResult
    {
        if (min($from, $to) <= 0 || $from === $to) {
            return null;
        }

        $adj = $this->buildAdjacencyList($cityId);
        $pathIds = $adj ? $this->dijkstra($from, $to, $adj) : [];

        return $pathIds ? $this->buildResult($pathIds) : null;
    }

    /**
     * Maps edges directly into an adjacency array with pre-calculated Haversine distances.
     */
    private function buildAdjacencyList(?int $cityId): array
    {
        return DB::table('bus_lines as bl')
            ->join('locations as lf', 'lf.id', '=', 'bl.from_location_id')
            ->join('locations as lt', 'lt.id', '=', 'bl.to_location_id')
            ->where('bl.status', StatusEnum::Active->value)
            ->when($cityId, fn($q) => $q->where('lf.city_id', $cityId))
            ->select([
                'bl.id', 'bl.from_location_id as from', 'bl.to_location_id as to',
                DB::raw('JSON_EXTRACT(lf.map_location, "$.lat") as f_lat, JSON_EXTRACT(lf.map_location, "$.lng") as f_lng'),
                DB::raw('JSON_EXTRACT(lt.map_location, "$.lat") as t_lat, JSON_EXTRACT(lt.map_location, "$.lng") as t_lng'),
            ])
            ->get()
            ->reduce(function (array $adj, object $edge) {
                $w = $this->haversine((float) $edge->f_lat, (float) $edge->f_lng, (float) $edge->t_lat, (float) $edge->t_lng);
                $adj[$edge->from][] = ['to' => $edge->to, 'line' => $edge->id, 'w' => $w];
                return $adj;
            }, []);
    }

    /**
     * Shortest path search utilizing a min-heap. Path history is tracked in the queue.
     */
    private function dijkstra(int $start, int $goal, array $adj): array
    {
        $pq = new SplPriorityQueue();
        $pq->insert([$start, []], 0); // [Current Node, Path History Array]
        $dist = [$start => 0.0];

        while ( ! $pq->isEmpty()) {
            [$node, $path] = $pq->extract();

            if ($node === $goal) {
                return $path;
            }

            foreach ($adj[$node] ?? [] as $edge) {
                $next = $edge['to'];
                $cost = $dist[$node] + $edge['w'] + self::TRANSFER_PENALTY_KM;

                if ($cost < ($dist[$next] ?? INF)) {
                    $dist[$next] = $cost;
                    $pq->insert([$next, [...$path, $edge['line']]], -$cost); // Negative cost for min-heap
                }
            }
        }

        return [];
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        if ( ! $lat1 || ! $lon1 || ! $lat2 || ! $lon2) {
            return 1.0;
        }
        $a = sin(deg2rad($lat2 - $lat1) / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin(deg2rad($lon2 - $lon1) / 2) ** 2;
        return 6371.0 * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /**
     * Reconstructs the segment data and unifies GeoJSON lines.
     */
    private function buildResult(array $lineIds): ?RouteFinderResult
    {
        $locale = app()->getLocale();
        $cacheKey = 'rf:geo:' . sha1(json_encode($lineIds) . config('app.key'));

        $lines = DB::table('bus_lines as bl')
            ->join('locations as lf', 'lf.id', '=', 'bl.from_location_id')
            ->join('locations as lt', 'lt.id', '=', 'bl.to_location_id')
            ->whereIn('bl.id', $lineIds)
            ->get(['bl.id', 'bl.from_location_id', 'bl.to_location_id', 'lf.name as f_name', 'lt.name as t_name'])
            ->keyBy('id');

        $media = DB::table('media')
            ->where('model_type', BusLine::class)->where('collection_name', 'route_json_file')
            ->whereIn('model_id', $lineIds)
            ->get(['id', 'model_id', 'disk', 'file_name'])->keyBy('model_id');

        $segments = [];
        $features = Cache::get($cacheKey);
        $needsGeoCache = null === $features;
        $features ??= [];

        foreach ($lineIds as $idx => $id) {
            if ( ! $line = $lines->get($id)) {
                continue;
            }

            $m = $media->get($id);
            $relPath = $m ? 'files/' . mb_substr(md5($m->id . config('app.key')), 0, 8) . "/{$m->file_name}" : null;
            $disk = Storage::disk($m->disk ?? 'public');

            $segments[] = [
                'id' => $id,
                'from_location_id' => $line->from_location_id,
                'to_location_id' => $line->to_location_id,
                'from_name' => json_decode($line->f_name, true)[$locale] ?? __('words.unknown'),
                'to_name' => json_decode($line->t_name, true)[$locale] ?? __('words.unknown'),
                'route_json_url' => $relPath ? $disk->url($relPath) : null,
                'segment_index' => $idx,
            ];

            if ($needsGeoCache && $relPath && is_file($path = $disk->path($relPath))) {
                $geo = json_decode(@file_get_contents($path) ?: '', true);
                $extracted = 'FeatureCollection' === $geo['type'] ? ($geo['features'] ?? []) : ('Feature' === $geo['type'] ? [$geo] : []);

                foreach ($extracted as $f) {
                    $f['properties'] = array_merge($f['properties'] ?? [], ['bus_line_id' => $id, 'segment_index' => $idx]);
                    $features[] = $f;
                }
            }
        }

        if (empty($segments)) {
            return null;
        }
        if ($needsGeoCache) {
            Cache::put($cacheKey, $features, now()->addMinutes(10));
        }

        return new RouteFinderResult($segments, ['type' => 'FeatureCollection', 'features' => $features]);
    }
}

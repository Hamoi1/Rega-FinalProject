<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\BusLine;
use App\Models\City;
use App\Models\Location;
use Illuminate\Database\Seeder;

class NearbyBusNetworkSeeder extends Seeder
{
    public int $locationsPerCity = 20;

    public float $radiusKm = 2.5;

    public int $connectionsPerStop = 3;

    public bool $attachRouteFiles = true;

    public function run(): void
    {
        if (0 === City::count()) {
            $this->call([CitySeeder::class]);
        }

        $cities = City::query()->orderBy('id')->get();
        if ($cities->isEmpty()) {
            return;
        }

        foreach ($cities as $city) {
            $locations = Location::query()
                ->where('city_id', $city->id)
                ->whereNotNull('map_location')
                ->get();

            if ($locations->count() < 2) {
                continue;
            }

            foreach ($locations as $from) {
                $nearest = $locations
                    ->filter(fn(Location $to): bool => $to->id !== $from->id)
                    ->map(function (Location $to) use ($from): array {
                        $fromCoords = is_array($from->map_location) ? $from->map_location : [];
                        $toCoords = is_array($to->map_location) ? $to->map_location : [];
                        $d = $this->haversineKm(
                            (float) data_get($fromCoords, 'lat', 0),
                            (float) data_get($fromCoords, 'lng', 0),
                            (float) data_get($toCoords, 'lat', 0),
                            (float) data_get($toCoords, 'lng', 0),
                        );

                        return ['to' => $to, 'd' => $d];
                    })
                    ->sortBy('d')
                    ->take($this->connectionsPerStop)
                    ->values();

                foreach ($nearest as $row) {
                    /** @var Location $to */
                    $to = $row['to'];

                    $this->createBusLineWithRoute($from, $to);
                    $this->createBusLineWithRoute($to, $from);
                }
            }
        }
    }

    private function createBusLineWithRoute(Location $from, Location $to): void
    {
        $busLine = BusLine::updateOrCreate([
            'from_location_id' => $from->id,
            'to_location_id' => $to->id,
        ], [
            'status' => StatusEnum::Active,
        ]);

        if ( ! $this->attachRouteFiles) {
            return;
        }

        $fromCoords = is_array($from->map_location) ? $from->map_location : [];
        $toCoords = is_array($to->map_location) ? $to->map_location : [];
        $fromLat = data_get($fromCoords, 'lat');
        $fromLng = data_get($fromCoords, 'lng');
        $toLat = data_get($toCoords, 'lat');
        $toLng = data_get($toCoords, 'lng');

        if ( ! is_numeric($fromLat) || ! is_numeric($fromLng) || ! is_numeric($toLat) || ! is_numeric($toLng)) {
            return;
        }

        $pointCount = random_int(4, 8);
        $deltaLat = (float) $toLat - (float) $fromLat;
        $deltaLng = (float) $toLng - (float) $fromLng;
        $noiseScale = min(max(max(abs($deltaLat), abs($deltaLng)) * 0.05, 0.0002), 0.005);

        $coordinates = [];
        foreach (range(0, $pointCount - 1) as $i) {
            $t = 1 === $pointCount ? 0 : $i / ($pointCount - 1);
            $lat = (float) $fromLat + ($deltaLat * $t);
            $lng = (float) $fromLng + ($deltaLng * $t);

            if (0 !== $i && $i !== $pointCount - 1) {
                $lat += fake()->randomFloat(7, -$noiseScale, $noiseScale);
                $lng += fake()->randomFloat(7, -$noiseScale, $noiseScale);
            }

            $coordinates[] = [$lng, $lat];
        }

        $geoJson = [
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'properties' => (object) [],
                    'geometry' => [
                        'type' => 'LineString',
                        'coordinates' => $coordinates,
                    ],
                ],
            ],
        ];

        $tmpBasePath = tempnam(sys_get_temp_dir(), 'nearby_route_');
        if (false === $tmpBasePath) {
            return;
        }

        $tmpJsonPath = $tmpBasePath . '.json';
        if ( ! @rename($tmpBasePath, $tmpJsonPath)) {
            $tmpJsonPath = $tmpBasePath;
        }

        $encodedGeoJson = json_encode($geoJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (false === $encodedGeoJson) {
            @unlink($tmpJsonPath);
            return;
        }

        file_put_contents($tmpJsonPath, $encodedGeoJson);

        $busLine->addMedia($tmpJsonPath)
            ->usingFileName('route_' . $busLine->id . '.json')
            ->toMediaCollection('route_json_file');

        @unlink($tmpJsonPath);
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $r * $c;
    }
}

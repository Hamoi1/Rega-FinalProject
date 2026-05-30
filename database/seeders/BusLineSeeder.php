<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\BusLine;
use App\Models\Location;
use Illuminate\Database\Seeder;

class BusLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cityId = \App\Models\City::query()->where('name->en', 'Sulaymaniyah')->value('id');
        $locationsQuery = Location::query();
        if ($cityId) {
            $locationsQuery->where('city_id', $cityId);
        }

        $locations = $locationsQuery->get()->keyBy(fn(Location $loc) => data_get($loc->name, 'en'));
        if ($locations->count() < 2) {
            return;
        }

        $pairs = [
            ['Sara Square', 'Sarchnar Park'],
            ['Sara Square', 'University of Sulaimani'],
            ['Sara Square', 'Family Mall'],
            ['Sara Square', 'Chwar Bagh Park'],
            ['Goizha', 'Sara Square'],
            ['Bakrajo', 'Sara Square'],
            ['Tasluja', 'University of Sulaimani'],
            ['Sulaimani International Airport', 'Sara Square'],
            ['Qaiwan City', 'Family Mall'],
            ['Sulaimani Bazaar', 'Sara Square'],
            ['Faruq Medical City', 'Sara Square'],
            ['Raparin', 'Sara Square'],
        ];

        foreach ($pairs as [$fromName, $toName]) {
            foreach ([[$fromName, $toName], [$toName, $fromName]] as [$a, $b]) {
                $fromLocation = $locations->get($a);
                $toLocation = $locations->get($b);
                if ( ! $fromLocation) {
                    continue;
                }

                if ( ! $toLocation) {
                    continue;
                }

                $busLine = BusLine::updateOrCreate([
                    'from_location_id' => $fromLocation->id,
                    'to_location_id' => $toLocation->id,
                ], [
                    'status' => StatusEnum::Active,
                ]);

                $from = $fromLocation->map_location ?? [];
                $to = $toLocation->map_location ?? [];
                $fromLat = data_get($from, 'lat');
                $fromLng = data_get($from, 'lng');
                $toLat = data_get($to, 'lat');
                $toLng = data_get($to, 'lng');
                if ( ! is_numeric($fromLat)) {
                    continue;
                }

                if ( ! is_numeric($fromLng)) {
                    continue;
                }

                if ( ! is_numeric($toLat)) {
                    continue;
                }

                if ( ! is_numeric($toLng)) {
                    continue;
                }

                $pointCount = random_int(4, 8);
                $deltaLat = (float) $toLat - (float) $fromLat;
                $deltaLng = (float) $toLng - (float) $fromLng;
                $noiseScale = min(max(max(abs($deltaLat), abs($deltaLng)) * 0.05, 0.0005), 0.01);

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

                $tmpBasePath = tempnam(sys_get_temp_dir(), 'route_');
                if (false === $tmpBasePath) {
                    continue;
                }

                $tmpJsonPath = $tmpBasePath . '.json';
                if ( ! @rename($tmpBasePath, $tmpJsonPath)) {
                    $tmpJsonPath = $tmpBasePath;
                }

                $encodedGeoJson = json_encode($geoJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (false === $encodedGeoJson) {
                    @unlink($tmpJsonPath);
                    continue;
                }

                file_put_contents($tmpJsonPath, $encodedGeoJson);

                $busLine->addMedia($tmpJsonPath)
                    ->usingFileName('route_' . $busLine->id . '.json')
                    ->toMediaCollection('route_json_file');

                @unlink($tmpJsonPath);
            }
        }
    }
}

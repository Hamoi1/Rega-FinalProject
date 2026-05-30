<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $city = City::query()->where('name->en', 'Sulaymaniyah')->first()
            ?? City::updateOrCreate([
                'name->en' => 'Sulaymaniyah',
            ], [
                'name' => [
                    'en' => 'Sulaymaniyah',
                    'ar' => 'السليمانية',
                    'ckb' => 'سلێمانی',
                ],
            ]);

        $locations = [
            ['en' => 'Sara Square', 'ar' => 'ساحة سارة', 'ckb' => 'چوارچڕای سارا', 'lat' => 35.5639, 'lng' => 45.4331],
            ['en' => 'Sarchnar Park', 'ar' => 'حديقة سرجنار', 'ckb' => 'سەرچنار', 'lat' => 35.5866, 'lng' => 45.4619],
            ['en' => 'University of Sulaimani', 'ar' => 'جامعة السليمانية', 'ckb' => 'زانکۆی سلێمانی', 'lat' => 35.5462, 'lng' => 45.4486],
            ['en' => 'Family Mall', 'ar' => 'فاميلي مول', 'ckb' => 'فامیلی مۆڵ', 'lat' => 35.5388, 'lng' => 45.4129],
            ['en' => 'Chwar Bagh Park', 'ar' => 'حديقة چوار باغ', 'ckb' => 'چوارباخ', 'lat' => 35.5695, 'lng' => 45.4322],
            ['en' => 'Salim Street', 'ar' => 'شارع سالم', 'ckb' => 'شەقامی سالم', 'lat' => 35.5651, 'lng' => 45.4310],
            ['en' => 'Goizha', 'ar' => 'گويژة', 'ckb' => 'گۆیژە', 'lat' => 35.5746, 'lng' => 45.4703],
            ['en' => 'Bakrajo', 'ar' => 'بكرجو', 'ckb' => 'بەکرەجو', 'lat' => 35.5038, 'lng' => 45.3925],
            ['en' => 'Tasluja', 'ar' => 'تاسلوجة', 'ckb' => 'تاسلوجە', 'lat' => 35.6127, 'lng' => 45.5098],
            ['en' => 'Raparin', 'ar' => 'رابرين', 'ckb' => 'ڕاپەڕین', 'lat' => 35.5608, 'lng' => 45.4572],
            ['en' => 'Qaiwan City', 'ar' => 'قيوان سيتي', 'ckb' => 'قایوان سیتی', 'lat' => 35.5712, 'lng' => 45.4069],
            ['en' => 'Faruq Medical City', 'ar' => 'مدينة فاروق الطبية', 'ckb' => 'شارۆچکەی پزیشکی فارووق', 'lat' => 35.5427, 'lng' => 45.4278],
            ['en' => 'Sulaimani Bazaar', 'ar' => 'بازار السليمانية', 'ckb' => 'بازاڕی سلێمانی', 'lat' => 35.5668, 'lng' => 45.4362],
            ['en' => 'Azadi Park', 'ar' => 'حديقة آزادي', 'ckb' => 'پارکی ئازادی', 'lat' => 35.5755, 'lng' => 45.4409],
            ['en' => 'Kurd Sat', 'ar' => 'كرد سات', 'ckb' => 'کوردسات', 'lat' => 35.5691, 'lng' => 45.4206],
            ['en' => 'Slemani Museum', 'ar' => 'متحف السليمانية', 'ckb' => 'مۆزەخانەی سلێمانی', 'lat' => 35.5660, 'lng' => 45.4349],
            ['en' => 'Nali Park', 'ar' => 'حديقة نالي', 'ckb' => 'پارکی ناڵی', 'lat' => 35.5662, 'lng' => 45.4416],
            ['en' => 'Sulaimani International Airport', 'ar' => 'مطار السليمانية الدولي', 'ckb' => 'فڕۆکەخانەی نێودەوڵەتی سلێمانی', 'lat' => 35.5607, 'lng' => 45.3149],
            ['en' => 'Shorsh Street', 'ar' => 'شارع شورش', 'ckb' => 'شەقامی شۆڕش', 'lat' => 35.5597, 'lng' => 45.4412],
            ['en' => 'Chwarchra Mall', 'ar' => 'مول چوارچرا', 'ckb' => 'مۆڵی چوارچڕا', 'lat' => 35.5634, 'lng' => 45.4327],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate([
                'name->en' => $location['en'],
            ], [
                'name' => [
                    'en' => $location['en'],
                    'ar' => $location['ar'],
                    'ckb' => $location['ckb'],
                ],
                'city_id' => $city->id,
                'map_location' => [
                    'lat' => $location['lat'],
                    'lng' => $location['lng'],
                ],
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User\Province;
use App\Models\User\City;
use Illuminate\Database\Seeder;

class ProvinceCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    $json = file_get_contents(storage_path('app/json/provinces_cities.json'));
    $data = json_decode($json, true);

    foreach ($data as $item) {
        $province = Province::firstOrCreate(['name' => $item['province']]);
        $cities = [];
        foreach ($item['cities'] as $cityName) {
            $cities[] = [
                'province_id' => $province->id,
                'name' => $cityName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        City::insert($cities);
    }
}
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Cairo',
            'Giza',
            'AlFayyum',
            'AlMinya',
            'Asyut',
            'Luxor',
            'Aswan',
            'Alexandria',
            'Port Said',
            'Suez'
        ];
        
        foreach ($cities as $city) {
            City::create(['name' => $city]);
        }
    }
}
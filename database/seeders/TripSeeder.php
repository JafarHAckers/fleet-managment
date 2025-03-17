<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\City;
use App\Models\TripStation;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all buses
        $buses = Bus::all();
        
        // Get all cities
        $cities = City::all();
        
        // Trip 1: Cairo to Aswan (via Giza, AlFayyum, AlMinya, Asyut, Luxor)
        $trip1 = Trip::create([
            'name' => 'Cairo to Aswan Express',
            'bus_id' => $buses[0]->id
        ]);
        
        // Create stations for Trip 1
        $tripStations = [
            ['city' => 'Cairo', 'order' => 1],
            ['city' => 'Giza', 'order' => 2],
            ['city' => 'AlFayyum', 'order' => 3],
            ['city' => 'AlMinya', 'order' => 4],
            ['city' => 'Asyut', 'order' => 5],
            ['city' => 'Luxor', 'order' => 6],
            ['city' => 'Aswan', 'order' => 7]
        ];
        
        foreach ($tripStations as $stationData) {
            $city = $cities->firstWhere('name', $stationData['city']);
            
            TripStation::create([
                'trip_id' => $trip1->id,
                'city_id' => $city->id,
                'order' => $stationData['order']
            ]);
        }
        
        // Trip 2: Alexandria to Port Said
        $trip2 = Trip::create([
            'name' => 'Alexandria to Port Said',
            'bus_id' => $buses[1]->id
        ]);
        
        // Create stations for Trip 2
        $trip2Stations = [
            ['city' => 'Alexandria', 'order' => 1],
            ['city' => 'Cairo', 'order' => 2],
            ['city' => 'Port Said', 'order' => 3]
        ];
        
        foreach ($trip2Stations as $stationData) {
            $city = $cities->firstWhere('name', $stationData['city']);
            
            TripStation::create([
                'trip_id' => $trip2->id,
                'city_id' => $city->id,
                'order' => $stationData['order']
            ]);
        }
        
        // Trip 3: Asyut to Cairo
        $trip3 = Trip::create([
            'name' => 'Asyut to Cairo',
            'bus_id' => $buses[2]->id
        ]);
        
        // Create stations for Trip 3
        $trip3Stations = [
            ['city' => 'Asyut', 'order' => 1],
            ['city' => 'AlMinya', 'order' => 2],
            ['city' => 'AlFayyum', 'order' => 3],
            ['city' => 'Giza', 'order' => 4],
            ['city' => 'Cairo', 'order' => 5]
        ];
        
        foreach ($trip3Stations as $stationData) {
            $city = $cities->firstWhere('name', $stationData['city']);
            
            TripStation::create([
                'trip_id' => $trip3->id,
                'city_id' => $city->id,
                'order' => $stationData['order']
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;
use App\Models\Seat;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 buses
        for ($i = 1; $i <= 5; $i++) {
            $bus = Bus::create([
                'name' => "Bus {$i}"
            ]);
            
            // Create 12 seats for each bus
            for ($j = 1; $j <= 12; $j++) {
                Seat::create([
                    'bus_id' => $bus->id,
                    'seat_number' => "S{$j}"
                ]);
            }
        }
    }
}
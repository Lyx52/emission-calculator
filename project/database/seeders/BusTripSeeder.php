<?php

namespace Database\Seeders;

use App\Models\BusTrip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BusTripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusTrip::query()->truncate();
        $disk = Storage::disk('seed_data');
        foreach(read_csv($disk->path('trips.csv')) as $row) {
            BusTrip::create([
                'id' => $row['trip_id'],
                'route_id' => empty(mb_trim($row['route_id'])) ? null : mb_trim($row['route_id']),
                'service_id' => empty(mb_trim($row['service_id'])) ? null : mb_trim($row['service_id']),
                'shape_id' => empty(mb_trim($row['shape_id'])) ? null : mb_trim($row['shape_id']),
                'trip_short_name' => $row['trip_short_name'],
            ]);
        }
    }
}

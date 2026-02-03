<?php

namespace Database\Seeders;

use App\Models\BusRoute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BusRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusRoute::query()->truncate();
        $disk = Storage::disk('seed_data');
        foreach(read_csv($disk->path('routes.csv')) as $row) {
            BusRoute::create([
                'id' => $row['route_id'],
                'agency_id' => empty(mb_trim($row['agency_id'])) ? null : mb_trim($row['agency_id']),
                'route_short_name' => mb_trim($row['route_short_name']),
                'route_long_name' => mb_trim($row['route_long_name']),
                'route_desc' => mb_trim($row['route_desc']),
                'route_type' => empty(mb_trim($row['route_type'])) ? null : mb_trim($row['route_type']),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\BusStop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BusStopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusStop::query()->truncate();
        $disk = Storage::disk('seed_data');
        foreach(read_csv($disk->path('stops.csv')) as $row) {
            BusStop::create([
                'id' => $row['stop_id'],
                'stop_code' => empty(mb_trim($row['stop_code'])) ? null : mb_trim($row['stop_code']),
                'stop_name' => mb_trim($row['stop_name']),
                'stop_desc' => mb_trim($row['stop_desc']),
                'stop_lat' => $row['stop_lat'],
                'stop_lon' => $row['stop_lon'],
                'zone_id' => empty(mb_trim($row['zone_id'])) ? null : mb_trim($row['zone_id']),
            ]);
        }
    }
}



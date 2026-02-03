<?php

namespace Database\Seeders;

use App\Models\BusStopTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BusStopTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusStopTime::query()->truncate();
        $disk = Storage::disk('seed_data');
        foreach(read_csv($disk->path('stop_times.csv')) as $row) {
            BusStopTime::create([
                'trip_id' => empty(mb_trim($row['trip_id'])) ? null : mb_trim($row['trip_id']),
                'arrival_time' => empty(mb_trim($row['arrival_time'])) ? null : mb_trim($row['arrival_time']),
                'departure_time' => empty(mb_trim($row['departure_time'])) ? null : mb_trim($row['departure_time']),
                'stop_id' => empty(mb_trim($row['stop_id'])) ? null : mb_trim($row['stop_id']),
                'stop_sequence' => $row['stop_sequence'],
            ]);
        }
    }
}

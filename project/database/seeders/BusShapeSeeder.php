<?php

namespace Database\Seeders;

use App\Models\BusShape;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BusShapeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusShape::query()->truncate();
        $disk = Storage::disk('seed_data');
        foreach (read_csv($disk->path('shapes.csv')) as $row) {
            BusShape::insert([
                'shape_id' => $row['shape_id'],
                'shape_pt_lat' => $row['shape_pt_lat'],
                'shape_pt_lon' => $row['shape_pt_lon'],
                'shape_pt_sequence' => $row['shape_pt_sequence'],
            ]);
        }
    }
}

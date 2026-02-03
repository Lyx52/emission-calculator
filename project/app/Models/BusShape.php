<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusShape extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'shape_id' => 'integer',
        'shape_pt_lat' => 'double',
        'shape_pt_lon' => 'double',
        'shape_pt_sequence' => 'integer',
    ];
}

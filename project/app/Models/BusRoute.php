<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusRoute extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'agency_id' => 'integer',
        'route_short_name' => 'string',
        'route_long_name' => 'string',
        'route_desc' => 'string',
        'route_type' => 'integer',
    ];
}

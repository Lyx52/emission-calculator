<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class BusStop extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'stop_code' => 'integer',
        'stop_name' => 'string',
        'stop_desc' => 'string',
        'stop_lat' => 'double',
        'stop_lon' => 'double',
        'zone_id' => 'integer',
    ];

    public function stopTime(): HasOne {
        return $this->hasOne(BusStopTime::class, 'stop_id', 'id');
    }

    public function trip(): HasOneThrough {
        return $this->hasOneThrough(BusTrip::class, BusStopTime::class, 'stop_id', 'id', 'id', 'trip_id');
    }

    public function coordinates(): Attribute {
        return Attribute::make(fn() => "{$this->stop_lat},{$this->stop_lon}");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BusTrip extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'route_id' => 'integer',
        'service_id' => 'integer',
        'shape_id' => 'integer',
        'trip_short_name' => 'string',
    ];

    public function shape(): HasMany {
        return $this->hasMany(BusShape::class, 'shape_id', 'shape_id');
    }

    public function stopTimes(): HasMany {
        return $this->hasMany(BusStopTime::class, 'trip_id', 'id');
    }

    public function route(): BelongsTo {
        return $this->belongsTo(BusRoute::class, 'route_id', 'id');
    }
}

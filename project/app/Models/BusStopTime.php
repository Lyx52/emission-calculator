<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusStopTime extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'trip_id' => 'integer',
        'arrival_time' => 'string',
        'departure_time' => 'string',
        'stop_id' => 'integer',
        'stop_sequence' => 'integer',
    ];

    public function trip(): BelongsTo {
        return $this->belongsTo(BusTrip::class, 'trip_id', 'id');
    }

    public function stop(): BelongsTo {
        return $this->belongsTo(BusStop::class, 'stop_id', 'id');
    }

    public function neighbourStops(): HasMany {
        return $this->hasMany(BusStopTime::class, 'trip_id', 'trip_id');
    }
}

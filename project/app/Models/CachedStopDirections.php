<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CachedStopDirections extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'trip_id' => 'integer',
        'from_stop_id' => 'integer',
        'to_stop_id' => 'integer',
        'response' => 'json'
    ];

    public function trip(): BelongsTo {
        return $this->belongsTo(BusTrip::class, 'trip_id');
    }

    public function fromStop(): BelongsTo {
        return $this->belongsTo(BusStop::class, 'from_stop_id');
    }

    public function toStop(): BelongsTo {
        return $this->belongsTo(BusStop::class, 'to_stop_id');
    }
}

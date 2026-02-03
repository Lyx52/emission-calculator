<?php

namespace App\Http\Controllers;

use App\Models\BusStop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusStopsController extends Controller
{
    public function query(Request $request) {
        $payload = $request->validate([
            'query' => 'required|string',
            'from' => 'nullable|integer',
        ]);

        $query = BusStop::query()
            ->leftJoinRelationship('stopTime')
            ->whereLike('bus_stops.stop_name', "%{$payload['query']}%");

        if (isset($payload['from'])) {
            $query->whereExists(function ($subQuery) use ($payload) {
                $subQuery->from('bus_stop_times as sub')
                    ->whereColumn('sub.trip_id', 'bus_stop_times.trip_id')
                    ->whereColumn('bus_stop_times.stop_sequence', '>', 'sub.stop_sequence')
                    ->where('sub.stop_id', $payload['from']);
            });
        }

        return new JsonResponse($query->limit(100)
            ->pluck('stop_name', 'id')
            ->toArray()
        );
    }
}

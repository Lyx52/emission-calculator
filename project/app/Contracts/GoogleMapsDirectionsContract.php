<?php
namespace App\Contracts;
use App\Models\CachedStopDirections;

interface GoogleMapsDirectionsContract {
    public function getDirectionsForTrip(int $tripId): ?CachedStopDirections;
    public function getDirectionsBetweenStops(int $fromStopId, int $toStopId): ?CachedStopDirections;
    public function getDirections(array $from, array $to): mixed;
}

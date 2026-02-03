<?php
namespace App\Services;
use App\Contracts\GoogleMapsDirectionsContract;
use App\Models\BusStop;
use App\Models\BusStopTime;
use App\Models\BusTrip;
use App\Models\CachedStopDirections;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class GoogleMapsDirectionsService implements GoogleMapsDirectionsContract
{
    private string $apiKey;
    private string $apiUrl;
    function __construct() {
        $this->apiKey = config('services.google.maps.key');
        $this->apiUrl = config('services.google.maps.url');
    }

    /**
     * @param array{float, float} $from
     * @param array{float, float} $to
     * @param array<int, array{float, float}> $waypoints
     * @return mixed
     * @throws ConnectionException
     */
    public function getDirections(array $from, array $to, array $waypoints = []): mixed {
        $properties = [
            'origin' => implode(',', $from),
            'destination' => implode(',', $to),
            'key' => $this->apiKey,
        ];

        if (!empty($waypoints)) {
            $properties['waypoints'] = implode('|', array_map(fn($point) => implode(',', $point), $waypoints));
        }

        $response = Http::baseUrl($this->apiUrl)
            ->get('/maps/api/directions/json', $properties);

        return $response->json();
    }

    public function getDirectionsBetweenStops(int $fromStopId, int $toStopId): ?CachedStopDirections
    {
        $existingEntry = CachedStopDirections::query()
            ->where('from_stop_id', $fromStopId)
            ->where('to_stop_id', $toStopId)
            ->first();

        if (!empty($existingEntry)) {
            return $existingEntry;
        }

        $from = BusStop::find($fromStopId);
        $to = BusStop::find($toStopId);
        $response = $this->getDirections([$from->stop_lat, $from->stop_lon], [$to->stop_lat, $to->stop_lon]);

        if ($response['status'] == 'OK') {
            return CachedStopDirections::create([
                'from_stop_id' => $fromStopId,
                'to_stop_id' => $toStopId,
                'response' => $response,
            ]);
        }

        return null;
    }

    public function getDirectionsForTrip(int $tripId): ?CachedStopDirections
    {
        $existingEntry = CachedStopDirections::query()
            ->where('trip_id', $tripId)
            ->first();

        if (!empty($existingEntry)) {
            return $existingEntry;
        }

        /** @var BusTrip $trip */
        $trip = BusTrip::find($tripId);
        if (empty($trip)) {
            return null;
        }
        $trip->load('stopTimes.stop');

        $from = null;
        $to = null;
        $waypoints = [];

        $stops = $trip->stopTimes->sortBy('stop_sequence')->pluck('stop')->all();

        /** @var BusStopTime $stop */
        foreach ($stops as $idx => $stop) {
            if (array_key_first($stops) === $idx) {
                $from = $stop;
                continue;
            }

            if (array_key_last($stops) === $idx) {
                $to = $stop;
                continue;
            }

            $waypoints[] = $stop;
        }

        $waypoints = array_map(fn($waypoint) => [$waypoint->stop_lat, $waypoint->stop_lon], $waypoints);
        $response = $this->getDirections([$from->stop_lat, $from->stop_lon], [$to->stop_lat, $to->stop_lon], $waypoints);

        if ($response['status'] == 'OK') {
            return CachedStopDirections::create([
                'trip_id' => $tripId,
                'response' => $response,
            ]);
        }

        return null;
    }
}

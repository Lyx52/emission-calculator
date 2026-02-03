<?php
namespace App\Services;
use App\Contracts\GoogleMapsDirectionsContract;
use App\Models\BusStop;
use App\Models\BusStopTime;
use App\Models\BusTrip;
use App\Models\CachedStopDirections;
use Illuminate\Database\Eloquent\Collection;
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
        $response = [
            'waypoints' => [],
            'legs' => [],
            'polylines' => [],
            'status' => true,
        ];

        $lastWaypoint = null;
        /** @var Collection $stopChunk */
        foreach ($trip->stopTimes->sortBy('stop_sequence')->pluck('stop')->chunk(25) as $stopChunk) {
            $from = $lastWaypoint ?? $stopChunk->first();
            $to = $stopChunk->last();
            $waypoints = [];
            /** @var BusStopTime $stop */
            foreach ($stopChunk->all() as $stop) {
                if ($from === $stop || $to === $stop) {
                    continue;
                }

                $waypoints[] = [$stop->stop_lat, $stop->stop_lon];
            }

            $result = $this->getDirections([$from->stop_lat, $from->stop_lon], [$to->stop_lat, $to->stop_lon], $waypoints);
            $route = end($result['routes']);
            if (empty($route)) {
                $response['status'] = false;
                break;
            }

            $response = [
                'waypoints' => [
                    ...$response['waypoints'],
                    ...$result['geocoded_waypoints'] ?? [],
                ],
                'legs' => [
                    ...$response['legs'],
                    ...$route['legs'] ?? []
                ],
                'polylines' => [
                    ...$response['polylines'],
                    $route['overview_polyline']['points'],
                ],
                'status' => $response['status'] && ($result['status'] === 'OK'),
            ];
            $lastWaypoint = $to;
        }

        if ($response['status']) {
            return CachedStopDirections::create([
                'trip_id' => $tripId,
                'response' => $response,
            ]);
        }

        return null;
    }
}

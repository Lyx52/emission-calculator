<?php

namespace App\Http\Controllers;

use App\Facades\GoogleMapsDirections;
use App\Models\BusTrip;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TripController extends Controller
{
    public function index(): View {
        return view('index');
    }

    public function directions(int $tripId): JsonResponse {
        $directions = GoogleMapsDirections::getDirectionsForTrip($tripId);

        return new JsonResponse($directions->response);
    }

    public function query(int $from, int $to): JsonResponse {
        /** @var BusTrip $trip */
        $trip = BusTrip::query()
            ->whereHas('stopTimes', function ($query) use ($from) {
                $query->where('stop_id', $from);
            })
            ->whereHas('stopTimes', function ($query) use ($to) {
                $query->where('stop_id', $to);
            })
            ->first();

        if (empty($trip)) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($trip->load(['stopTimes.stop', 'route'])->toArray());
    }
}

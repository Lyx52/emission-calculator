<?php

use App\Http\Controllers\BusStopsController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;
Route::prefix('api')->group(function () {
    Route::prefix('stops')->group(function () {
        Route::get('/query', [BusStopsController::class, 'query']);
    });
    Route::prefix('trips')->group(function () {
        Route::get('/directions/{tripId}', [TripController::class, 'directions']);
        Route::get('/{from}/{to}', [TripController::class, 'query']);
    });
});

Route::get('/trips', [TripController::class, 'index']);
Route::get('/map', [TripController::class, 'map']);

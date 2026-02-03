<?php
namespace App\Facades;
use App\Contracts\GoogleMapsDirectionsContract;
use Illuminate\Support\Facades\Facade;

class GoogleMapsDirections extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GoogleMapsDirectionsContract::class;
    }
}

<?php

namespace App\Services\Locations\Users;

use App\Http\Resources\LocationResource;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class UserLocationService
{
    public function __construct() {}
    public function getLocation(float $long,  float $lat)
    {
        if (env('APP_DEBUG') != true) {
            $location_data = Http::withHeaders([
                'User-Agent' => env("APP_NAME") . ' (aliensaif@gmail.com)'
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                "accept-language" => "en",
                'lat' => $lat,
                'lon' => $long,
                'format' => 'json'
            ]);
            $country = Arr::get($location_data, 'address.country');
            $city = Arr::get($location_data, 'address.city');
            return new LocationResource($country, $city, $long, $lat);
        } else {
            // for resting TODO: think about this
            return new LocationResource('test', 'test', 24.86, 67.01);
        }
    }
}

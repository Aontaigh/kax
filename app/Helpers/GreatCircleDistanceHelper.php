<?php

namespace App\Helpers;

class GreatCircleDistanceHelper
{
    /**
     * Calculates the great-circle distance between two points with the Haversine Formula.
     *
     * @param  float  $latitudeFrom latitude of start point in deg decimal
     * @param  float  $longitudeFrom longitude of start point in deg decimal
     * @param  float  $latitudeTo latitude of target point in deg decimal
     * @param  float  $longitudeTo longitude of target point in deg decimal
     * @param  float  $earthRadius mean earth radius in metres
     * @return float  distance between points in metres
     */
    public static function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo   = deg2rad($latitudeTo);
        $lonTo   = deg2rad($longitudeTo);
  
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
  
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}

<?php

namespace yidas\GoogleMaps\Services;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/distance-matrix/
 */
class DistanceMatrix extends AbstractService
{

    public function getPath(): string
    {
        return '/maps/api/distancematrix/json';
    }

    /**
     * Distance matrix
     *
     * @param string $origins
     * @param string $destinations
     * @param array<string, string|int|float> $params Query parameters
     * @return array<string, string|int|float>
     */
    public function distanceMatrix(string $origins, string $destinations, array $params=[])
    {
        $params['origins'] = (string) $origins;
        $params['destinations'] = (string) $destinations;

        return $params;
    }
}

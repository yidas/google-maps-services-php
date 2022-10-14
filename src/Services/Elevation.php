<?php

namespace yidas\GoogleMaps\Services;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/elevation/
 */
class Elevation extends AbstractService
{
    const API_PATH = '/maps/api/elevation/json';

    /**
     * Elevation
     *
     * @param string $locations
     * @param array<string, string|int|float> $params Query parameters
     * @return array<mixed> Result
     */
    public function elevation($locations, $params=[])
    {
        // `locations` seems to only allow `lat,lng` pattern
        if (is_string($locations)) {
            
            $params['locations'] = $locations;

        } else {

            list($lat, $lng) = $locations;
            $params['locations'] = "{$lat},{$lng}";
        }

        return $this->requestHandler(self::API_PATH, $params);
    }
}

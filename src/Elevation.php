<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/elevation/
 */
class Elevation extends Service
{
    const API_PATH = '/maps/api/elevation/json';

    /**
     * Elevation
     *
     * @param Client $client
     * @param string $locations 
     * @param array Query parameters
     * @return array Result
     */
    public static function elevation(Client $client, $locations, $params=[])
    {
        // `locations` seems to only allow `lat,lng` pattern
        if (is_string($locations)) {
            
            $params['locations'] = $locations;

        } else {

            list($lat, $lng) = $locations;
            $params['locations'] = "{$lat},{$lng}";
        }

        return self::requestHandler($client, self::API_PATH, $params);
    }
}

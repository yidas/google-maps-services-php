<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/timezone/
 */
class Timezone extends Service
{
    const API_PATH = '/maps/api/timezone/json';

    /**
     * Timezone
     *
     * @param Client $client
     * @param string $location 
     * @param string $timestamp
     * @param array Query parameters
     * @return array Result
     */
    public static function timezone(Client $client, $location, $timestamp=null, $params=[])
    {
        // `location` seems to only allow `lat,lng` pattern
        if (is_string($location)) {
            
            $params['location'] = $location;

        } else {

            list($lat, $lng) = $location;
            $params['location'] = "{$lat},{$lng}";
        }

        // Timestamp
        $params['timestamp'] = ($timestamp) ?: time();

        return self::requestHandler($client, self::API_PATH, $params);
    }
}

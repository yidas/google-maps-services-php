<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/geolocation/
 */
class Geolocation extends Service
{
    /**
     * Replace all
     */
    const API_PATH = 'https://www.googleapis.com/geolocation/v1/geolocate';

    /**
     * Geolocate
     *
     * @param Client $client
     * @param array Body parameters
     * @return array Result
     */
    public static function geolocate(Client $client, $bodyParams=[])
    {
        // Google API request body format
        $body = json_encode($bodyParams);
        
        return self::requestHandler($client, self::API_PATH, [], 'POST', $body);
    }
}

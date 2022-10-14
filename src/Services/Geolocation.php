<?php

namespace yidas\GoogleMaps\Services;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/geolocation/
 */
class Geolocation extends AbstractService
{
    /**
     * Replace all
     */
    const API_PATH = 'https://www.googleapis.com/geolocation/v1/geolocate';

    /**
     * Geolocate
     *
     * @param array Body parameters
     * @return array<mixed> Result
     */
    public function geolocate($bodyParams=[])
    {
        // Google API request body format
        $body = json_encode($bodyParams);
        
        return $this->requestHandler(self::API_PATH, [], 'POST', $body);
    }
}

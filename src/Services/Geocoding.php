<?php

namespace yidas\GoogleMaps\Services;

/**
 * Geocoding Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/geocoding/
 */
class Geocoding extends AbstractService
{
    const API_PATH = '/maps/api/geocode/json';

    /**
     * Reverse Geocode
     *
     * @param string $address
     * @param array<string, string|int|float> $params Query parameters
     * @return array<mixed> Result
     */
    public function geocode($address=null, $params=[])
    {
        if (is_string($address)) {
            $params['address'] = $address;
        }

        return $this->requestHandler(self::API_PATH, $params);
    }

    /**
     * Reverse Geocode
     *
     * @param array<string|float>|string $latlng ['lat', 'lng'] or place_id string
     * @param array<string, string|int|float> $params Query parameters
     * @return array<mixed> Result
     */
    public function reverseGeocode($latlng, $params=[])
    {
        // Check if latlng param is a place_id string.
        // place_id strings do not contain commas; latlng strings do.
        if (is_string($latlng)) {
            
            $params['place_id'] = $latlng;

        } else {

            list($lat, $lng) = $latlng;
            $params['latlng'] = "{$lat},{$lng}";
        }

        return $this->requestHandler(self::API_PATH, $params);
    }
}

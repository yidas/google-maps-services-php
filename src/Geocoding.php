<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Geocoding Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/geocoding/
 */
class Geocoding extends Service
{
    const API_PATH = '/maps/api/geocode/json';

    /**
     * Reverse Geocode
     *
     * @param Client $client
     * @param string $address
     * @param array $params
     * @return array Result
     */
    public static function geocode(Client $client, $address=null, $params=[])
    {
        if (is_string($address)) 
            $params['address'] = $address;

        return self::requestHandler($client, self::API_PATH, $params);
    }

    /**
     * Reverse Geocode
     *
     * @param Client $client
     * @param array|string $latlng ['lat', 'lng'] or place_id string
     * @param array $params
     * @return array Result
     */
    public static function reverseGeocode(Client $client, $latlng, $params=[])
    {
        // Check if latlng param is a place_id string.
        // place_id strings do not contain commas; latlng strings do.
        if (is_string($latlng)) {
            $test = array_map("trim",explode(",",$latlng));
            if(2 == count($test) && is_numeric($test[0]) && is_numeric($test[1])){
            	$params['latlng'] = "{$test[0]},{$test[1]}";
            } else {
	            $params['place_id'] = $latlng;
            }
        } else {
            list($lat, $lng) = $latlng;
            $params['latlng'] = "{$lat},{$lng}";
        }

        return self::requestHandler($client, self::API_PATH, $params);
    }
}

<?php

namespace yidas\GoogleMaps\Services;

use LogicException;

/**
 * Geocoding Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/geocoding/
 */
class Geocoding extends AbstractService
{

    public function getPath(): string
    {
        return '/maps/api/geocode/json';
    }

    /**
     * Reverse Geocode
     *
     * @param string $address
     * @param array<string, string|int|float> $params Query parameters
     * @return array<string, string|int|float>
     */
    public function geocode($address=null, $params=[]): array
    {
        if (is_string($address)) {
            $params['address'] = $address;
        }

        return $params;
    }

    /**
     * Reverse Geocode
     *
     * @param array<string|float>|string $latlng ['lat', 'lng'] or place_id string
     * @param array<string, string|int|float> $params Query parameters
     * @return array<string, string|int|float>
     */
    public function reverseGeocode($latlng, array $params=[])
    {
        // Check if latlng param is a place_id string.
        // place_id strings do not contain commas; latlng strings do.
        if (is_string($latlng)) {
            
            $params['place_id'] = $latlng;

        } elseif (isset($latlng['lat']) && isset($latlng['lng'])) {

            $params['latlng'] = sprintf('%1.08F,%1.08F', $latlng['lat'], $latlng['lng']);

        } elseif (isset($latlng[0]) && isset($latlng[1])) {

            $params['latlng'] = sprintf('%1.08F,%1.08F', $latlng[0], $latlng[1]);

        } else {

            throw new LogicException('Passed invalid values into coordinates! You must use either array with lat and lng or 0 and 1 keys.');

        }

        return $params;
    }
}

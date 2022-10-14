<?php

namespace yidas\GoogleMaps\Services;

/**
 * Timezone Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/timezone/
 */
class Timezone extends AbstractService
{
    const API_PATH = '/maps/api/timezone/json';

    /**
     * Timezone
     *
     * @param string|array<string> $location
     * @param string $timestamp
     * @param array<string, string|int|float> $params Query parameters
     * @return array<mixed> Result
     */
    public function timezone($location, $timestamp=null, $params=[])
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

        return $this->requestHandler($this->getPath(), $params);
    }

    public function getPath(): string
    {
        return self::API_PATH;
    }
}

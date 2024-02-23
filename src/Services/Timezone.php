<?php

namespace yidas\GoogleMaps\Services;

use yidas\GoogleMaps\ServiceException;

/**
 * Timezone Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see     https://developers.google.com/maps/documentation/timezone/
 */
class Timezone extends AbstractMapService
{
    public function getPath(): string
    {
        return static::API_HOST . '/maps/api/timezone/json';
    }

    /**
     * Timezone
     *
     * @param string|array<string> $location
     * @param int $timestamp
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return array<string, string|int|float>
     */
    public function timezone($location, ?int $timestamp=null, array $params=[]): array
    {
        // `location` seems to only allow `lat,lng` pattern
        if (is_string($location)) {
            
            $params['location'] = $location;

        } else {

            if (isset($location['lat']) && isset($location['lng'])) {

                $params['location'] = sprintf('%1.08F,%1.08F', $location['lat'], $location['lng']);

            } elseif (isset($location[0]) && isset($location[1])) {

                $params['location'] = sprintf('%1.08F,%1.08F', $location[0], $location[1]);

            } else {

                throw new ServiceException('Passed invalid values into coordinates! You must use either array with lat and lng or 0 and 1 keys.');

            }
        }

        // Timestamp
        $params['timestamp'] = ($timestamp) ?: time();

        return $this->extendQueryParams($params);
    }
}

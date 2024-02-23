<?php

namespace yidas\GoogleMaps\Services;

use yidas\GoogleMaps\ServiceException;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see     https://developers.google.com/maps/documentation/elevation/
 * @see     https://developers.google.com/maps/documentation/elevation/requests-elevation
 */
class Elevation extends AbstractMapService
{
    public function getPath(): string
    {
        return static::API_HOST . '/maps/api/elevation/json';
    }

    /**
     * Elevation
     *
     * @param string|array<string|int, float> $locations
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return array<string, string|int|float>
     */
    public function elevation($locations, array $params=[]): array
    {
        // `locations` seems to only allow `lat,lng` pattern
        if (is_string($locations)) {

            $params['locations'] = $locations;

        } elseif (isset($locations['lat']) && isset($locations['lng'])) {

            $params['locations'] = sprintf('%1.08F,%1.08F', $locations['lat'], $locations['lng']);

        } elseif (isset($locations[0]) && isset($locations[1])) {

            $params['locations'] = sprintf('%1.08F,%1.08F', $locations[0], $locations[1]);

        } else {

            throw new ServiceException('Passed invalid values into coordinates! You must use either preformatted string or array with lat and lng or 0 and 1 keys.');

        }

        return $this->extendQueryParams($params);
    }
}

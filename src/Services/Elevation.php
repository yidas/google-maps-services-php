<?php

namespace yidas\GoogleMaps\Services;

use LogicException;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/elevation/
 */
class Elevation extends AbstractService
{

    public function getPath(): string
    {
        return '/maps/api/elevation/json';
    }

    /**
     * Elevation
     *
     * @param string|array<string|int, float> $locations
     * @param array<string, string|int|float> $params Query parameters
     * @throws LogicException
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

            throw new LogicException('Passed invalid values into coordinates! You must use either preformatted string or array with lat and lng or 0 and 1 keys.');

        }

        return $params;
    }
}

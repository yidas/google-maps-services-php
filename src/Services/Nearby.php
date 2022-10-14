<?php

namespace yidas\GoogleMaps\Services;

use LogicException;

/**
 * Nearby service
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @since   2.0.0
 * @see https://developers.google.com/maps/documentation/places/web-service/search-nearby
 */
class Nearby extends AbstractService
{
    public function getPath(): string
    {
        return '/maps/api/place/nearbysearch/json';
    }

    /**
     * Nearby lookup
     *
     * @param string $keyword
     * @param array<string|int, float> $latlng ['lat', 'lng']
     * @param float|null $radius
     * @param string|null $type as wanted by Google
     * @param array<string, string|int|float> $params Query parameters
     * @throws LogicException
     * @return array<string, string|int|float>
     */
    public function nearby(string $keyword, array $latlng = [], ?float $radius = null, ?string $type = null, $params=[])
    {
        if (empty($keyword) && empty($latlng)) {
            throw new LogicException('You must set where to look!');
        }

        // Main wanted name
        if (!empty($keyword)) {
            $params['keyword'] = $keyword;
        }

        // `location` seems to only allow `lat,lng` pattern
        if (!empty($latlng)) {

            if (isset($latlng['lat']) && isset($latlng['lng'])) {

                $params['latlng'] = sprintf('%1.08F,%1.08F', $latlng['lat'], $latlng['lng']);

            } elseif (isset($latlng[0]) && isset($latlng[1])) {

                $params['latlng'] = sprintf('%1.08d,%1.08d', $latlng[0], $latlng[1]);

            } else {

                throw new LogicException('Passed invalid values into coordinates! You must use either array with lat and lng or 0 and 1 keys.');

            }
        }

        if (!empty($radius)) {
            $params['radius'] = $radius;
        }

        if (!empty($type)) {
            $params['type'] = $type;
        }

        return $params;
    }
}

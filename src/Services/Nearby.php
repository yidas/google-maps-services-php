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
    const API_PATH = '/maps/api/place/nearbysearch/json';

    /**
     * Nearby lookup
     *
     * @param string $keyword
     * @param array<string, float> $latlng ['lat', 'lng']
     * @param float|null $radius
     * @param string|null $type as wanted by Google
     * @param array<string, string|int|float> $params Query parameters
     * @throws LogicException
     * @return array<mixed> Result
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
            list($lat, $lng) = $latlng;
            $params['latlng'] = "{$lat},{$lng}";
        }

        if (!empty($radius)) {
            $params['radius'] = $radius;
        }

        if (!empty($type)) {
            $params['type'] = $type;
        }

        return $this->requestHandler(self::API_PATH, $params);
    }
}

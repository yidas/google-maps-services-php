<?php

namespace yidas\googleMaps\Services;

/**
 * Directions Service
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see     https://developers.google.com/maps/documentation/directions/
 * @see     https://developers.google.com/maps/documentation/directions/get-directions
 */
class Directions extends AbstractMapService
{
    public function getPath(): string
    {
        return static::API_HOST . '/maps/api/directions/json';
    }

    /**
     * Directions
     *
     * @param string $origin
     * @param string $destination
     * @param array<string, string|int|float> $params Query parameters
     * @return array<string, string|int|float>
     */
    public function directions(string $origin, string $destination, array $params = []): array
    {
        $params['origin'] = (string) $origin;
        $params['destination'] = (string) $destination;

        return $this->extendQueryParams($params);
    }
}

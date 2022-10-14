<?php

namespace yidas\GoogleMaps\Services;

/**
 * Directions Service
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see     https://developers.google.com/maps/documentation/directions/
 */
class Directions extends AbstractService
{
    const API_PATH = '/maps/api/directions/json';

    /**
     * Directions
     *
     * @param string $origin
     * @param string $destination
     * @param array<string, string|int|float> $params Query parameters
     * @return array<mixed> Result
     */
    public function directions($origin, $destination, $params = [])
    {
        $params['origin'] = (string) $origin;
        $params['destination'] = (string) $destination;

        return $this->requestHandler(self::API_PATH, $params);
    }
}

<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Directions Service
 *
 * @author  Mark Scherer <dereuromark@web.de>
 * @since   1.1.0
 * @see https://developers.google.com/maps/documentation/routes/
 */
class Routes extends Service
{
    const API_PATH = 'https://routes.googleapis.com/directions/v2:computeRoutes';

    /**
     * Distance matrix
     *
     * @param Client $client
     * @param array $origin
     * @param array $destination
     * @param array Query parameters
     * @return array Result
     */
    public static function route(Client $client, $origin, $destination, $params=[])
    {
        $requestData = $params;
        $requestData['origin'] = $origin;
        $requestData['destination'] = $destination;
        $params = [];

        $params['headers'] = [
            'Content-Type' => 'application/json',
            'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.legs,geocodingResults',
        ];
        $params['body'] = json_encode($requestData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return self::requestHandler($client, self::API_PATH, $params, 'POST');
    }
}

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
    const API_URL = 'https://routes.googleapis.com/directions/v2:computeRoutes';

    const LANGUAGE_METHOD = 'body';

    /**
     * Distance matrix
     *
     * @param Client $client
     * @param array $origin
     * @param array $destination
     * @param array $body Full body
     * @param array $headers
     * @return array Result
     */
    public static function route(Client $client, $origin, $destination, $body=[], $headers=[])
    {
        $requestBody = $body;
        $requestBody['origin'] = $origin ?? $requestBody['origin'] ?? [];
        $requestBody['destination'] = $destination ?? $requestBody['destination'] ?? [];

        // Header
        $defaultHeaders = [
            'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.legs,geocodingResults',
        ];
        $headers = array_merge($defaultHeaders, $headers);

        return self::requestHandler($client, self::API_URL, [], 'POST', $requestBody, $headers);
    }
}

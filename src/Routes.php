<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Directions Service
 *
 * @author  Mark Scherer <dereuromark@web.de>
 * @since   1.2.0
 * @see https://developers.google.com/maps/documentation/routes/
 */
class Routes extends Service
{
    const API_URL = 'https://routes.googleapis.com/directions/v2';

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
    public static function computeRoutes(Client $client, $origin, $destination, $body=[], $fieldMask=[])
    {
        $fullApiUrl = self::API_URL . ':computeRoutes';
        
        $requestBody = $body;
        $requestBody['origin'] = $origin ?? $requestBody['origin'] ?? [];
        $requestBody['destination'] = $destination ?? $requestBody['destination'] ?? [];

        // Header
        $fieldMask = $fieldMask ? $fieldMask : ['routes.duration', 'routes.distanceMeters', 'routes.polyline.encodedPolyline'];
        $fieldMask = is_array($fieldMask) ? implode(",", $fieldMask) : $fieldMask;
        $headers = [
            'X-Goog-Api-Key' => $client->getApiKey(),
            'X-Goog-FieldMask' => $fieldMask,
        ];

        return self::requestHandler($client, $fullApiUrl, [], 'POST', $requestBody, $headers);
    }
}

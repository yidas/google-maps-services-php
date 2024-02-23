<?php

namespace yidas\GoogleMaps\Services;

use yidas\GoogleMaps\ServiceException;

/**
 * Routes service
 *
 * @author  Mark Scherer <dereuromark@web.de>
 * @since   1.1.0
 * @see https://developers.google.com/maps/documentation/routes/
 * @see https://developers.google.com/maps/documentation/routes/compute_route_directions
 */
class Routes extends AbstractService
{
    /**
     * @var array<string, string>
     */
    protected $headers = [];

    public function getPath(): string
    {
        return 'https://routes.googleapis.com/directions/v2:computeRoutes';
    }

    /**
     * @throws ServiceException
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        $params = $this->auth->getAuthParams();
        if (!isset($params['key'])) {
            throw new ServiceException('No correct API key set!');
        }
        return array_merge([
            'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.legs,geocodingResults',
            'X-Goog-Api-Key' => $params['key'],
        ], $this->headers);
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * Route lookup
     *
     * @param array<mixed>|null $origin
     * @param array<mixed>|null $destination
     * @param array<mixed>|null $body Full body
     * @param array<string, string> $headers
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return array<string, string|int|float>
     */
    public function route($origin, $destination, $body=[], array $headers=[], array $params=[]): array
    {
        $requestBody = $body;
        $requestBody['origin'] = $origin ?? $requestBody['origin'] ?? [];
        $requestBody['destination'] = $destination ?? $requestBody['destination'] ?? [];

        // Language Code
        if (!empty($this->language)) {
            $requestBody['languageCode'] = $this->language;
        }

        // Google API request body format
        $body = @json_encode($requestBody);
        if (false === $body) {
            // @codeCoverageIgnoreStart
            // to get this error you must have something really fishy in $bodyParams
            throw new ServiceException(json_last_error_msg());
        }
        // @codeCoverageIgnoreEnd
        $this->body = $body;
        $this->headers = $headers;

        return $params;
    }
}

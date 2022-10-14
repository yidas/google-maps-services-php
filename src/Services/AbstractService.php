<?php

namespace yidas\GoogleMaps\Services;

use yidas\GoogleMaps\Clients\AbstractClient;

/**
 * Google Maps Abstract Service
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 */
abstract class AbstractService
{
    /**
     * Client to send requests
     * @var AbstractClient
     */
    protected $client = null;

    public function __construct(AbstractClient $client)
    {
        $this->client = $client;
    }

    /**
     * Request Handler
     *
     * @param string $apiPath
     * @param array<string|int|float> $params
     * @param string $method HTTP request method
     * @param string $body HTTP body to amend
     * @return array|mixed Formatted result
     */
    protected function requestHandler(string $apiPath, array $params, string $method = 'GET', ?string $body = null)
    {
        $response = $this->client->request($apiPath, $params, $method, $body);
        $result = json_decode($response->getMessageBody(), true);

        // Error Handler
        if (200 != $response->getStatusCode()) {
            return $result;
        } elseif (isset($result['error_message'])) {
            // Error message Checker (200 situation form Google Maps API)
            return $result;
        }

        // `results` parsing from Google Maps API, while pass parsing on error
        return isset($result['results']) ? $result['results'] : $result;
    }
}

<?php

namespace yidas\googleMaps;

/**
 * Google Maps Abstract Service
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.1.0
 */
abstract class Service
{
    /**
     * Define by each service
     *
     * @param string
     */
    const API_PATH = '';

    /**
     * Request Handler
     *
     * @param Client $client
     * @param string $apiPath
     * @param array $params
     * @param string $method HTTP request method
     * @param array $body
     * @param array $headers
     * @return array|mixed Formated result
     */
    protected static function requestHandler(Client $client, $apiPath, $params, $method='GET', $body=[], $headers=[])
    {
        // Body
        $bodyString = ($body) ? json_encode($body, JSON_UNESCAPED_SLASHES) : null;

        // Header
        $defaultHeaders = [
            'Content-Type' => 'application/json',
        ];
        $headers = array_merge($defaultHeaders, $headers);

        $response = $client->request($apiPath, $params, $method, $bodyString, $headers);
        $result = $response->getBody()->getContents();
        $result = json_decode($result, true);

        // Error Handler
        if (200 != $response->getStatusCode())
            return $result;
        // Error message Checker (200 situation form Google Maps API)
        elseif (isset($result['error_message']))
            return $result;

        // `results` parsing from Google Maps API, while pass parsing on error
        return  isset($result['results']) ? $result['results'] : $result;
    }
}

<?php

namespace yidas\googleMaps;

/**
 * Google Maps Abstract Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
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
     * @return array|mixed Formated result
     */
    protected static function requestHandler(Client $client, $apiPath, $params, $method='GET')
    {
        $response = $client->request($apiPath, $params, $method);
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

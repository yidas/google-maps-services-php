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
    const API_URL = '';

    /**
     * Language setting method
     * 
     * 'query' for query string method, 'body' for request body
     *
     * @param string
     */
    const LANGUAGE_METHOD = 'query';

    /**
     * Request Handler
     *
     * @param Client $client
     * @param string $apiUrl
     * @param array $params
     * @param string $method HTTP request method
     * @param array $body
     * @param array $headers
     * @return array|mixed Formated result
     */
    protected static function requestHandler(Client $client, $apiUrl, $params, $method='GET', $body=[], $headers=[])
    {
        // Language setting for query string
        if (static::LANGUAGE_METHOD && $client->getLanguage()) {
            
            switch (static::LANGUAGE_METHOD) {
                case 'body':
                    if (!isset($body['languageCode'])) {     
                        $body['languageCode'] = $client->getLanguage();
                    }
                    break;
                
                case 'query':
                default:
                    $params['language'] = $client->getLanguage();
                    break;
            }
        }
        
        // Body
        $bodyString = ($body) ? json_encode($body, JSON_UNESCAPED_SLASHES) : null;

        // Header
        $defaultHeaders = [
            'Content-Type' => 'application/json',
        ];
        $headers = array_merge($defaultHeaders, $headers);

        $response = $client->request($apiUrl, $params, $method, $bodyString, $headers);
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

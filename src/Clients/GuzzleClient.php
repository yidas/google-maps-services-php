<?php

namespace yidas\GoogleMaps\Clients;

use Exception;
use GuzzleHttp\Client as HttpClient;
use yidas\GoogleMaps\ApiAuth;

/**
 * Google Maps PHP Client
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @version 1.0.0
 * @codeCoverageIgnore because external resources
 */
class GuzzleClient extends AbstractClient
{
    /**
     * Guzzle Http Client
     *
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * Google Api Auth class providing auth params
     *
     * @var ApiAuth
     */
    protected $auth;

    /**
     * Constructor
     *
     * @param ApiAuth $auth Google Api class to get auth params
     * @throws Exception
     */
    public function __construct(ApiAuth $auth)
    {
        // Load GuzzleHttp\Client
        $this->httpClient = new HttpClient([
            'base_uri' => ApiAuth::API_HOST,
            'timeout' => 5.0,
        ]);
        $this->auth = $auth;
    }

    /**
     * Request Google Map API
     *
     * @param string $apiPath
     * @param array<string, string|int|float> $params
     * @param string $method HTTP request method
     * @param string $body
     * @return AbstractResponse
     */
    public function request(string $apiPath, array $params = [], string $method = 'GET', ?string $body = null): AbstractResponse
    {
        // Guzzle request options
        $options = [
            'http_errors' => false,
        ];

        // Parameters for Auth
        $defaultParams = $this->auth->getAuthParams();
        // Parameters for Language setting
        if ($this->language) {
            $defaultParams['language'] = $this->language;
        }

        // Query
        $options['query'] = array_merge($defaultParams, $params);

        // Body
        if ($body) {
            $options['body'] = $body;
        }

        return new PsrResponse($this->httpClient->request($method, $apiPath, $options));
    }
}
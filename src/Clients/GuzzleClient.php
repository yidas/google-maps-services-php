<?php

namespace yidas\GoogleMaps\Clients;

use Exception;
use GuzzleHttp\Client as HttpClient;

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
     * Constructor
     *
     * @throws Exception
     */
    public function __construct()
    {
        // Load GuzzleHttp\Client
        $this->httpClient = new HttpClient([
            'timeout' => 5.0,
        ]);
    }

    /**
     * Request Google Map API
     *
     * @param string $apiPath
     * @param array<string, string|int|float> $query
     * @param string $method HTTP request method
     * @param string[] $headers
     * @param string $body
     * @return AbstractResponse
     */
    public function request(string $apiPath, array $query = [], string $method = 'GET', array $headers = [], ?string $body = null): AbstractResponse
    {
        // Guzzle request options
        $options = [
            'http_errors' => false,
        ];

        // Query
        $options['query'] = $query;

        // Body
        if ($body) {
            $options['body'] = $body;
        }

        // Headers
        if ($headers) {
            $options['headers'] = $headers;
        }

        return new PsrResponse($this->httpClient->request($method, $apiPath, $options));
    }
}

<?php

namespace yidas\GoogleMaps\Clients;

use Exception;
use yidas\GoogleMaps\ApiAuth;

/**
 * Google Maps PHP Client by
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.1.0
 *
 * Ask by PHP internals, no need to call Guzzle
 */
class PhpClient extends AbstractClient
{
    /**
     * @var array<string, mixed>
     */
    protected $httpParams;

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
        $this->httpParams = [
            'base_uri' => ApiAuth::API_HOST,
            'timeout' => 5.0,
        ];
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
        // Parameters for Auth
        $defaultParams = $this->auth->getAuthParams();
        // Parameters for Language setting
        if ($this->language) {
            $defaultParams['language'] = $this->language;
        }

        // Query
        $query = array_merge($defaultParams, $params);
        $address =
            (false !== strpos($apiPath, '://') ? '' : $this->httpParams['base_uri'])
            . $apiPath . '?' . http_build_query($query);

        return new PhpResponse($this->postToServer($address, $this->contextDataForPost($method, $body)));
    }

    /**
     * @param string $method
     * @param string|null $body
     * @return array<mixed>
     */
    public function contextDataForPost(string $method, ?string $body): array
    {
        $http = [];

        if (!empty($body)) {
            $http['content'] = $body;
            $http['header'] = implode("\r\n", [
                'Content-type: application/json',
                'Content-length: ' . strlen($body)
            ]);
        }

        return [
            'ssl' => [
//                'verify_peer' => false,
//                'verify_peer_name' => false,
//                'allow_self_signed' => true,
            ],
            'http' => array_merge([
                'method' => strtoupper($method),
                'timeout' => intval($this->httpParams['timeout'])
            ], $http)
        ];
    }

    /**
     * @param string $address
     * @param array<mixed> $contextData
     * @return string
     * @codeCoverageIgnore because external resources
     */
    protected function postToServer(string $address, array $contextData): string
    {
        return file_get_contents($address, false, stream_context_create($contextData)); // php 7.1+
    }
}

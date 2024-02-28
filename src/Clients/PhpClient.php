<?php

namespace yidas\googleMaps\Clients;

use UnexpectedValueException;

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
     * @var float
     */
    protected $timeout = 5.0;

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
        // Address
        $address = $apiPath . '?' . http_build_query($query);

        return new PhpResponse($this->postToServer($address, $this->contextDataForPost($method, $headers, $body)));
    }

    /**
     * @param string $method
     * @param string[] $headers
     * @param string|null $body
     * @return array<mixed>
     */
    public function contextDataForPost(string $method, array $headers, ?string $body): array
    {
        $http = [];

        if (!empty($body)) {
            // geolocation and other services with request body
            $http['content'] = $body;
            $headers = array_merge($headers, [
                'Content-type' => 'application/json',
                'Content-length' => strlen($body)
            ]);
        }
        if (!empty($headers)) {
            $http['header'] = implode("\r\n", array_map(
                [$this, 'stringifyHeader'],
                array_map('strval', array_keys($headers)),
                array_map('strval', array_values($headers))
            ));
        }

        return [
            'ssl' => [
//                'verify_peer' => false,
//                'verify_peer_name' => false,
//                'allow_self_signed' => true,
            ],
            'http' => array_merge([
                'method' => strtoupper($method),
                'timeout' => intval($this->timeout)
            ], $http)
        ];
    }

    public function stringifyHeader(string $key, string $value): string
    {
        return sprintf('%s: %s', $key, $value);
    }

    /**
     * @param string $address
     * @param array<mixed> $contextData
     * @return string
     * @codeCoverageIgnore because external resources
     */
    protected function postToServer(string $address, array $contextData): string
    {
        $content = file_get_contents($address, false, stream_context_create($contextData)); // php 7.1+
        if (false === $content) {
            throw new UnexpectedValueException('Something failed on query.');
        }
        return $content;
    }
}

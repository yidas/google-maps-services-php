<?php

namespace yidas\googleMaps\Clients;

/**
 * Google Maps PHP Client - abstract client for usage different HTTP implementations
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.0.0
 **/
abstract class AbstractClient
{
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
    abstract public function request(string $apiPath, array $query = [], string $method = 'GET', array $headers = [], ?string $body = null): AbstractResponse;
}

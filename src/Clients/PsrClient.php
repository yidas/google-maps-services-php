<?php

namespace yidas\GoogleMaps\Clients;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use yidas\GoogleMaps\ServiceException;

/**
 * Google Maps PSR Client
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 3.1.0
 */
class PsrClient extends AbstractClient
{
    /**
     * PSR Http Client
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Constructor
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Request Google API
     *
     * @param string $apiPath
     * @param array<string, string|int|float> $query
     * @param string $method HTTP request method
     * @param string[] $headers
     * @param string $body
     * @throws ClientExceptionInterface
     * @throws ServiceException
     * @return AbstractResponse
     */
    public function request(string $apiPath, array $query = [], string $method = 'GET', array $headers = [], ?string $body = null): AbstractResponse
    {
        // Query
        $uri = new Psr\SimplifiedUri($apiPath);
        $uri->withQuery(http_build_query($query));

        $request = new Psr\SimplifiedRequest(
            $uri,
            new Psr\SimplifiedBody(strval($body))
        );
        $request->withMethod($method);

        // Headers
        if ($headers) {
            foreach ($headers as $key => $value) {
                $request->withHeader($key, $value);
            }
        }

        return new PsrResponse($this->client->sendRequest($request));
    }
}

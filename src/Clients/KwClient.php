<?php

namespace yidas\googleMaps\Clients;

use kalanis\RemoteRequest;

/**
 * Google Maps RemoteRequest Client by
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.1.0
 *
 * Ask by RemoteRequest library, no need to call Guzzle and Curl
 */
class KwClient extends AbstractClient
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
     * @throws RemoteRequest\RequestException
     * @return AbstractResponse
     */
    public function request(string $apiPath, array $query = [], string $method = 'GET', array $headers = [], ?string $body = null): AbstractResponse
    {
        // Address
        $parsedLink = parse_url($apiPath);
        if ((false === $parsedLink) || empty($parsedLink["host"]) || empty($parsedLink['path'])) {
            // to get this error you must pass unrecognizable address into parser
            throw new RemoteRequest\RequestException('Link parser got something strange.');
        }

        $schema = !empty($parsedLink["scheme"]) ? strtolower($parsedLink["scheme"]) : '' ;
        switch ($schema) {
            case 'tcp':
            case 'http':
                $libParams = new RemoteRequest\Connection\Params\Tcp();
                $port = 80;
                break;
            case 'ssl':
            case 'https':
                $libParams = new RemoteRequest\Connection\Params\Ssl();
                $port = 443;
                break;
            default:
                throw new RemoteRequest\RequestException('Invalid schema in query');
        }

        $libParams->setTarget(
            strval($parsedLink["host"]),
            empty($parsedLink["port"]) ? $port : intval($parsedLink["port"]),
            intval($this->timeout)
        );

        $libQuery = new Kw\Query(); # http rest internals
        $libQuery
            ->setMethod(strtolower($method))
            ->setRequestSettings($libParams)
            ->setPath($parsedLink['path'] . '?' . http_build_query($query))
        ;

        // Body
        if ($body) {
            $libQuery->body = $body;
        }

        if ($headers) {
            $libQuery->addHeaders($headers);
        }

        $libHttpAnswer = new RemoteRequest\Protocols\Http\Answer();
        return new KwResponse($libHttpAnswer->setResponse($this->process($libParams, $libQuery)));
    }

    /**
     * @param RemoteRequest\Connection\Params\AParams $params
     * @param RemoteRequest\Interfaces\IQuery $query
     * @throw RemoteRequest\RequestException
     * @return resource|null
     * @codeCoverageIgnore because external resources
     */
    protected function process(RemoteRequest\Connection\Params\AParams $params, RemoteRequest\Interfaces\IQuery $query)
    {
        $libProcessor = new RemoteRequest\Connection\Processor(); # tcp/ip http/ssl
        $libProcessor->setConnectionParams($params);
        $libProcessor->setData($query);
        return $libProcessor->getResponse();
    }
}

<?php

namespace yidas\GoogleMaps\Clients;

use kalanis\RemoteRequest;
use yidas\GoogleMaps\ApiAuth;
use UnexpectedValueException;

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
     * @var array<string, mixed>
     */
    protected $httpParams;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->httpParams = [
            'base_uri' => ApiAuth::API_HOST,
            'timeout' => 5.0,
        ];
    }

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
        $address =
            (false !== strpos($apiPath, '://') ? '' : $this->httpParams['base_uri'])
            . $apiPath;

        $parsedLink = parse_url($address);
        if ((false === $parsedLink) || empty($parsedLink["host"]) || empty($parsedLink['path'])) {
            // @codeCoverageIgnoreStart
            // to get this error you must pass unrecognizable address into parser
            throw new UnexpectedValueException('Link parser got something strange.');
        }
        // @codeCoverageIgnoreEnd

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
            intval($this->httpParams['timeout'])
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

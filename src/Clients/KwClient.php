<?php

namespace yidas\GoogleMaps\Clients;

use Exception;
use kalanis\RemoteRequest;
use yidas\GoogleMaps\ApiAuth;

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
     * @throws RemoteRequest\RequestException
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
            . $apiPath;

        $parsedLink = parse_url($address);

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

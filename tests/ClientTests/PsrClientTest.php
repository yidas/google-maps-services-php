<?php

namespace ClientTests;


use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use yidas\googleMaps\Clients\PsrClient;
use yidas\googleMaps\ServiceException;


class PsrClientTest extends \CommonTestClass
{
    /**
     * @param string $result
     * @param string $path
     * @param array<string, string> $params
     * @param string $method
     * @param array<string, string> $headers
     * @param string|null $body
     * @throws ClientExceptionInterface
     * @throws ServiceException
     * @dataProvider requestProvider
     */
    public function testRequest(string $result, string $path, array $params, string $method, array $headers, ?string $body): void
    {
        $lib = new PsrClient(new XClient());
        $this->assertEquals($result, $lib->request($path, $params, $method, $headers, $body)->getMessageBody());
    }

    public function requestProvider(): array
    {
        return [
            ['https://maps.googleapis.com/maps/api/directions/json?destination=Montreal&origin=Toronto'
                . AResponse::DELIMITER . AResponse::DELIMITER . ''
                , 'https://maps.googleapis.com/maps/api/directions/json', ['destination'=>'Montreal', 'origin'=>'Toronto'], 'gEt', [], null
            ],
            ['https://maps.googleapis.com/geolocation/v1/geolocate'
                . AResponse::DELIMITER . AResponse::DELIMITER . 'foo=bar&baz=zgv'
                , 'https://maps.googleapis.com/geolocation/v1/geolocate', [], 'PoSt', [], 'foo=bar&baz=zgv'
            ],
            ['http://localhost/any/v1/somewhere?key=test'
                . AResponse::DELIMITER . AResponse::DELIMITER . ''
                , 'http://localhost/any/v1/somewhere', ['key' => 'test'], 'get', ['X-foo' => 'bar'], null
            ],
        ];
    }
}


class XClient implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return new \XHttp($this->postToServer($request->getMethod(), $request->getUri()->__toString(), $request->getBody()->getContents()));
    }

    protected function postToServer(string $method, string $address, string $contextData): string
    {
        return 'HTTP/0.1 ' . $method . ' PASS' . AResponse::DELIMITER
            . AResponse::DELIMITER
            . $address . AResponse::DELIMITER
            . AResponse::DELIMITER
            . $contextData;
    }
}

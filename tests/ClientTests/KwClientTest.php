<?php

namespace ClientTests;


use kalanis\RemoteRequest;
use yidas\googleMaps\Clients\KwClient;


class KwClientTest extends \CommonTestClass
{
    /**
     * @param string $result
     * @param string $path
     * @param array<string, string> $params
     * @param string $method
     * @param array<string, string> $headers
     * @param string|null $body
     * @throws RemoteRequest\RequestException
     * @dataProvider requestProvider
     */
    public function testRequest(string $result, string $path, array $params, string $method, array $headers, ?string $body): void
    {
        $lib = new XKwClient();
        $this->assertEquals($result, $lib->request($path, $params, $method, $headers, $body)->getMessageBody());
    }

    public function requestProvider(): array
    {
        return [
            ['ssl://---maps.googleapis.com---443---5'
                . AResponse::DELIMITER . AResponse::DELIMITER . 's:168:"GET /maps/api/directions/json?destination=Montreal&origin=Toronto HTTP/1.1'
                . AResponse::DELIMITER . 'Host: maps.googleapis.com:443'
                . AResponse::DELIMITER . 'Accept: */*'
                . AResponse::DELIMITER . 'User-Agent: php-agent/1.3'
                . AResponse::DELIMITER . 'Connection: close'
                . AResponse::DELIMITER . AResponse::DELIMITER . '";'
                , 'https://maps.googleapis.com/maps/api/directions/json', ['destination'=>'Montreal', 'origin'=>'Toronto'], 'gEt', [], null
            ],
            ['ssl://---maps.googleapis.com---443---5'
                . AResponse::DELIMITER . AResponse::DELIMITER . 's:230:"POST /geolocation/v1/geolocate? HTTP/1.1'
                . AResponse::DELIMITER . 'Host: maps.googleapis.com:443'
                . AResponse::DELIMITER . 'Accept: */*'
                . AResponse::DELIMITER . 'User-Agent: php-agent/1.3'
                . AResponse::DELIMITER . 'Connection: close'
                . AResponse::DELIMITER . 'X-Header-data: pass-to-test'
                . AResponse::DELIMITER . 'Content-Length: 15'
                . AResponse::DELIMITER . 'Content-Type: application/json'
                . AResponse::DELIMITER . AResponse::DELIMITER . 'foo=bar&baz=zgv";'
                , 'https://maps.googleapis.com/geolocation/v1/geolocate', [], 'PoSt', ['X-Header-data' => 'pass-to-test'], 'foo=bar&baz=zgv'
            ],
            ['tcp://---localhost---80---5'
                . AResponse::DELIMITER . AResponse::DELIMITER . 's:119:"GET /any/v1/somewhere?key=test HTTP/1.1'
                . AResponse::DELIMITER . 'Host: localhost'
                . AResponse::DELIMITER . 'Accept: */*'
                . AResponse::DELIMITER . 'User-Agent: php-agent/1.3'
                . AResponse::DELIMITER . 'Connection: close'
                . AResponse::DELIMITER . AResponse::DELIMITER . '";'
                , 'http://localhost/any/v1/somewhere', ['key' => 'test'], 'get', [], null
            ],
        ];
    }

    /**
     * @throws RemoteRequest\RequestException
     */
    public function testRequestFail(): void
    {
        $lib = new XKwClient();
        $this->expectExceptionMessage('Invalid schema in query');
        $this->expectException(RemoteRequest\RequestException::class);
        $lib->request('fsp://watafaka/', [], 'oof');
    }

    /**
     * @throws RemoteRequest\RequestException
     */
    public function testRequestFail2(): void
    {
        $lib = new XKwClient();
        $this->expectExceptionMessage('Link parser got something strange.');
        $this->expectException(RemoteRequest\RequestException::class);
        $lib->request('/just/relative/address', [], 'oof');
    }
}


class XKwClient extends KwClient
{
    protected function process(RemoteRequest\Connection\Params\AParams $params, RemoteRequest\Interfaces\IQuery $query)
    {
        $f = fopen('php://memory', 'r+');
        fwrite($f, $this->postToServer(
            $params->getSchema() . '---' . $params->getHost() . '---' . $params->getPort() . '---' . $params->getTimeout()
        , stream_get_contents($query->getData(), -1, 0)));
        rewind($f);
        return $f;
    }

    protected function postToServer(string $address, $contextData): string
    {
        return 'HTTP/0.1 999 PASS' . AResponse::DELIMITER
            . AResponse::DELIMITER
            . $address . AResponse::DELIMITER
            . AResponse::DELIMITER
            . serialize($contextData);
    }
}

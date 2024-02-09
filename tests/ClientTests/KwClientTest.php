<?php

namespace ClientTests;


use kalanis\RemoteRequest;
use yidas\GoogleMaps\ApiAuth;
use yidas\GoogleMaps\Clients\KwClient;


class KwClientTest extends \CommonTestClass
{
    /**
     * @param string $result
     * @param string $path
     * @param array<string, string> $params
     * @param string $method
     * @param string|null $body
     * @param string|null $language
     * @throws \Exception
     * @dataProvider requestProvider
     */
    public function testRequest(string $result, string $path, array $params, string $method, ?string $body, ?string $language): void
    {
        $lib = new XKwClient(new ApiAuth('test'));
        if ($language) {
            $lib->setLanguage($language);
        }
        $this->assertEquals($result, $lib->request($path, $params, $method, $body)->getMessageBody());
    }

    public function requestProvider(): array
    {
        return [
            ['ssl://---maps.googleapis.com---443---5'
                . AResponse::DELIMITER . AResponse::DELIMITER . 's:189:"GET /maps/api/directions/json?key=test&language=hk&destination=Montreal&origin=Toronto HTTP/1.1'
                . AResponse::DELIMITER . 'Host: maps.googleapis.com:443'
                . AResponse::DELIMITER . 'Accept: */*'
                . AResponse::DELIMITER . 'User-Agent: php-agent/1.3'
                . AResponse::DELIMITER . 'Connection: close'
                . AResponse::DELIMITER . AResponse::DELIMITER . '";'
                , '/maps/api/directions/json', ['destination'=>'Montreal', 'origin'=>'Toronto'], 'gEt', null, 'hk'
            ],
            ['ssl://---maps.googleapis.com---443---5'
                . AResponse::DELIMITER . AResponse::DELIMITER . 's:209:"POST /geolocation/v1/geolocate?key=test HTTP/1.1'
                . AResponse::DELIMITER . 'Host: maps.googleapis.com:443'
                . AResponse::DELIMITER . 'Accept: */*'
                . AResponse::DELIMITER . 'User-Agent: php-agent/1.3'
                . AResponse::DELIMITER . 'Connection: close'
                . AResponse::DELIMITER . 'Content-Length: 15'
                . AResponse::DELIMITER . 'Content-Type: application/json'
                . AResponse::DELIMITER . AResponse::DELIMITER . 'foo=bar&baz=zgv";'
                , '/geolocation/v1/geolocate', [], 'PoSt', 'foo=bar&baz=zgv', null
            ],
            ['tcp://---localhost---80---5'
                . AResponse::DELIMITER . AResponse::DELIMITER . 's:119:"GET /any/v1/somewhere?key=test HTTP/1.1'
                . AResponse::DELIMITER . 'Host: localhost'
                . AResponse::DELIMITER . 'Accept: */*'
                . AResponse::DELIMITER . 'User-Agent: php-agent/1.3'
                . AResponse::DELIMITER . 'Connection: close'
                . AResponse::DELIMITER . AResponse::DELIMITER . '";'
                , 'http://localhost/any/v1/somewhere', [], 'get', null, null
            ],
        ];
    }

    /**
     * @throws \Exception
     */
    public function testRequestFail(): void
    {
        $lib = new XKwClient(new ApiAuth('test'));
        $this->expectExceptionMessage('Invalid schema in query');
        $this->expectException(\Exception::class);
        $lib->request('fsp://watafaka/', [], 'oof');
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

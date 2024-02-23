<?php

namespace ClientTests;


use yidas\GoogleMaps\Clients\PhpClient;


class PhpClientTest extends \CommonTestClass
{
    /**
     * @param string $result
     * @param string $path
     * @param array<string, string> $params
     * @param string $method
     * @param array<string, string> $headers
     * @param string|null $body
     * @dataProvider requestProvider
     */
    public function testRequest(string $result, string $path, array $params, string $method, array $headers, ?string $body): void
    {
        $lib = new XPhpClient();
        $this->assertEquals($result, $lib->request($path, $params, $method, $headers, $body)->getMessageBody());
    }

    public function requestProvider(): array
    {
        return [
            ['https://maps.googleapis.com/maps/api/directions/json?destination=Montreal&origin=Toronto'
                . AResponse::DELIMITER . AResponse::DELIMITER . 'a:2:{s:3:"ssl";a:0:{}s:4:"http";a:2:{s:6:"method";s:3:"GET";s:7:"timeout";i:5;}}'
                , '/maps/api/directions/json', ['destination'=>'Montreal', 'origin'=>'Toronto'], 'gEt', [], null
            ],
            ['https://maps.googleapis.com/geolocation/v1/geolocate?'
                . AResponse::DELIMITER . AResponse::DELIMITER . 'a:2:{s:3:"ssl";a:0:{}s:4:"http";a:4:{s:6:"method";s:4:"POST";s:7:"timeout";i:5;s:7:"content";s:15:"foo=bar&baz=zgv";s:6:"header";s:50:"Content-type: application/json'.AResponse::DELIMITER.'Content-length: 15";}}'
                , '/geolocation/v1/geolocate', [], 'PoSt', [], 'foo=bar&baz=zgv'
            ],
            ['http://localhost/any/v1/somewhere?key=test'
                . AResponse::DELIMITER . AResponse::DELIMITER . 'a:2:{s:3:"ssl";a:0:{}s:4:"http";a:2:{s:6:"method";s:3:"GET";s:7:"timeout";i:5;}}'
                , 'http://localhost/any/v1/somewhere', ['key' => 'test'], 'get', [], null
            ],
        ];
    }
}


class XPhpClient extends PhpClient
{
    protected function postToServer(string $address, array $contextData): string
    {
        return 'HTTP/0.1 999 PASS' . AResponse::DELIMITER
            . AResponse::DELIMITER
            . $address . AResponse::DELIMITER
            . AResponse::DELIMITER
            . serialize($contextData);
    }
}

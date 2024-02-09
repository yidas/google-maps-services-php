<?php

namespace ClientTests;


use yidas\GoogleMaps\ApiAuth;
use yidas\GoogleMaps\Clients\PhpClient;


class PhpClientTest extends \CommonTestClass
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
        $lib = new XPhpClient(new ApiAuth('test'));
        if ($language) {
            $lib->setLanguage($language);
        }
        $this->assertEquals($result, $lib->request($path, $params, $method, $body)->getMessageBody());
    }

    public function requestProvider(): array
    {
        return [
            ['https://maps.googleapis.com/maps/api/directions/json?key=test&language=hk&destination=Montreal&origin=Toronto'
                . AResponse::DELIMITER . AResponse::DELIMITER . 'a:2:{s:3:"ssl";a:0:{}s:4:"http";a:2:{s:6:"method";s:3:"GET";s:7:"timeout";i:5;}}'
                , '/maps/api/directions/json', ['destination'=>'Montreal', 'origin'=>'Toronto'], 'gEt', null, 'hk'
            ],
            ['https://maps.googleapis.com/geolocation/v1/geolocate?key=test'
                . AResponse::DELIMITER . AResponse::DELIMITER . 'a:2:{s:3:"ssl";a:0:{}s:4:"http";a:4:{s:6:"method";s:4:"POST";s:7:"timeout";i:5;s:7:"content";s:15:"foo=bar&baz=zgv";s:6:"header";s:50:"Content-type: application/json'.AResponse::DELIMITER.'Content-length: 15";}}'
                , '/geolocation/v1/geolocate', [], 'PoSt', 'foo=bar&baz=zgv', null
            ],
            ['http://localhost/any/v1/somewhere?key=test'
                . AResponse::DELIMITER . AResponse::DELIMITER . 'a:2:{s:3:"ssl";a:0:{}s:4:"http";a:2:{s:6:"method";s:3:"GET";s:7:"timeout";i:5;}}'
                , 'http://localhost/any/v1/somewhere', [], 'get', null, null
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

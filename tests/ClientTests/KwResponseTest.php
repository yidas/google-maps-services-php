<?php

namespace ClientTests;


use kalanis\RemoteRequest\Protocols;
use kalanis\RemoteRequest\RequestException;
use yidas\GoogleMaps\Clients\KwResponse;


class KwResponseTest extends AResponse
{
    /**
     * @param string $in
     * @param int $code
     * @param string $body
     * @throws RequestException
     * @dataProvider responseProvider
     */
    public function testResponse(string $in, int $code, string $body): void
    {
        $inside = new Protocols\Http\Answer();
        $inside->setResponse($in);
        $lib = new KwResponse($inside);
        $this->assertEquals($code, $lib->getStatusCode());
        $this->assertEquals($body, $lib->getMessageBody());
    }
}

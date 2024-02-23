<?php

namespace ClientTests;


use yidas\GoogleMaps\Clients\PsrResponse;


class PsrResponseTest extends AResponse
{
    /**
     * @param string $in
     * @param int $code
     * @param string $body
     * @dataProvider responseProvider
     */
    public function testResponse(string $in, int $code, string $body): void
    {
        $lib = new PsrResponse(new \XHttp($in));
        $this->assertEquals($code, $lib->getStatusCode());
        $this->assertEquals($body, $lib->getMessageBody());
    }
}

<?php

namespace ClientTests;


use yidas\GoogleMaps\Clients\PhpResponse;


class PhpResponseTest extends AResponse
{
    /**
     * @param string $in
     * @param int $code
     * @param string $body
     * @dataProvider responseProvider
     */
    public function testResponse(string $in, int $code, string $body): void
    {
        $lib = new PhpResponse($in);
        $this->assertEquals($code, $lib->getStatusCode());
        $this->assertEquals($body, $lib->getMessageBody());
    }
}

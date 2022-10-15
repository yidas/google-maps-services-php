<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class BasicsTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new XService();
        $this->assertEquals('dummy path', $lib->getPath());
        $this->assertEquals('GET', $lib->getMethod());
        $this->assertEquals(null, $lib->getBody());
        $this->assertEquals(true, $lib->wantInnerResult());
    }
}


class XService extends Services\AbstractService
{
    public function getPath(): string
    {
        return 'dummy path';
    }
}

<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class BasicsTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new XService(new ApiAuth('test'));
        $this->assertEquals('dummy path', $lib->getPath());
        $this->assertEquals('GET', $lib->getMethod());
        $this->assertEquals([], $lib->getHeaders());
        $this->assertNull($lib->getBody());
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

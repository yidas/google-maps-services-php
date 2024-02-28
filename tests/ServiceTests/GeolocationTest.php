<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class GeolocationTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\Geolocation(new ApiAuth('test'));
        $this->assertEquals('https://www.googleapis.com/geolocation/v1/geolocate', $lib->getPath());
        $this->assertEquals('POST', $lib->getMethod());
        $this->assertEquals(null, $lib->getBody());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLocate(): void
    {
        $lib = new Services\Geolocation(new ApiAuth('test'));
        $this->assertNull($lib->getBody());
        $this->assertEquals(['key' => 'test',], $lib->geolocate([10.7, 11.4, 'foo' => 'bar']));
        $this->assertEquals('{"0":10.7,"1":11.4,"foo":"bar"}', $lib->getBody());
    }
}

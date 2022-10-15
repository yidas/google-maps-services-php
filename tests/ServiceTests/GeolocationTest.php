<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class GeolocationTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\Geolocation();
        $this->assertEquals('https://www.googleapis.com/geolocation/v1/geolocate', $lib->getPath());
        $this->assertEquals('POST', $lib->getMethod());
        $this->assertEquals(null, $lib->getBody());
    }

    /**
     * @throws Exception
     */
    public function testServiceLocate(): void
    {
        $lib = new Services\Geolocation();
        $this->assertNull($lib->getBody());
        $this->assertEquals([], $lib->geolocate([10.7, 11.4, 'foo' => 'bar']));
        $this->assertEquals('{"0":10.7,"1":11.4,"foo":"bar"}', $lib->getBody());
    }
}

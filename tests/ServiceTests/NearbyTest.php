<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class NearbyTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\Nearby();
        $this->assertEquals('/maps/api/place/nearbysearch/json', $lib->getPath());
        $this->assertEquals([
            'keyword' => 'foo',
            'radius' => 10.2,
            'type' => 'bar',
        ], $lib->nearby('foo', [], 10.2, 'bar'));
    }

    /**
     * @throws Exception
     */
    public function testServiceLatLng(): void
    {
        $lib = new Services\Nearby();
        $this->assertEquals([
            'latlng' => '10.70000000,11.40000000',
        ], $lib->nearby('', [10.7, 11.4]));
        $this->assertEquals([
            'latlng' => '20.50000000,22.80000000',
        ], $lib->nearby('', ['lat' => 20.5, 'lng' => 22.8]));
    }

    /**
     * @throws Exception
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new Services\Nearby();
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(Exception::class);
        $lib->nearby('', []);
    }

    /**
     * @throws Exception
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\Nearby();
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(Exception::class);
        $lib->nearby('', ['foo' => 'bar']);
    }
}

<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class GeocodingTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\Geocoding();
        $this->assertEquals('/maps/api/geocode/json', $lib->getPath());
        $this->assertEquals([
            'address' => 'somewhere in the land',
        ], $lib->geocode('somewhere in the land'));
        $this->assertEquals([
            'place_id' => 'you do not know where',
        ], $lib->reverseGeocode('you do not know where'));
    }

    /**
     * @throws Exception
     */
    public function testServiceLatLng(): void
    {
        $lib = new Services\Geocoding();
        $this->assertEquals([
            'latlng' => '10.70000000,11.40000000',
        ], $lib->reverseGeocode([10.7, 11.4]));
        $this->assertEquals([
            'latlng' => '20.50000000,22.80000000',
        ], $lib->reverseGeocode(['lat' => 20.5, 'lng' => 22.8]));
    }

    /**
     * @throws Exception
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\Geocoding();
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(Exception::class);
        $lib->reverseGeocode(['foo' => 'bar']);
    }
}

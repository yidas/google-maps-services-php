<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class GeocodingTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\Geocoding(new ApiAuth('test'));
        $this->assertEquals('https://maps.googleapis.com/maps/api/geocode/json', $lib->getPath());
        $this->assertEquals([
            'address' => 'somewhere in the land',
            'key' => 'test',
        ], $lib->geocode('somewhere in the land'));
        $this->assertEquals([
            'place_id' => 'you do not know where',
            'key' => 'test',
        ], $lib->reverseGeocode('you do not know where'));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLatLng(): void
    {
        $lib = new Services\Geocoding(new ApiAuth('test'));
        $this->assertEquals([
            'latlng' => '10.70000000,11.40000000',
            'key' => 'test',
        ], $lib->reverseGeocode([10.7, 11.4]));
        $this->assertEquals([
            'latlng' => '20.50000000,22.80000000',
            'key' => 'test',
        ], $lib->reverseGeocode(['lat' => 20.5, 'lng' => 22.8]));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\Geocoding(new ApiAuth('test'));
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(ServiceException::class);
        $lib->reverseGeocode(['foo' => 'bar']);
    }
}

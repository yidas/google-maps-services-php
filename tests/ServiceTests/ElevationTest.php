<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class ElevationTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\Elevation(new ApiAuth('test'));
        $this->assertEquals('https://maps.googleapis.com/maps/api/elevation/json', $lib->getPath());
        $this->assertEquals([
            'locations' => 'you do not know where',
            'key' => 'test',
        ], $lib->elevation('you do not know where'));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLatLng(): void
    {
        $lib = new Services\Elevation(new ApiAuth('test'));
        $this->assertEquals([
            'locations' => '10.70000000,11.40000000',
            'key' => 'test',
        ], $lib->elevation([10.7, 11.4]));
        $this->assertEquals([
            'locations' => '20.50000000,22.80000000',
            'key' => 'test',
        ], $lib->elevation(['lat' => 20.5, 'lng' => 22.8]));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\Elevation(new ApiAuth('test'));
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(ServiceException::class);
        $lib->elevation(['foo' => 'bar']);
    }
}

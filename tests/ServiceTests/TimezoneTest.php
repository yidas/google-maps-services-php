<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\GoogleMaps\ApiAuth;
use yidas\GoogleMaps\ServiceException;
use yidas\GoogleMaps\Services;


class TimezoneTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\Timezone(new ApiAuth('test'));
        $this->assertEquals('/maps/api/timezone/json', $lib->getPath());
        $this->assertEquals([
            'location' => '10.1, 20.2',
            'timestamp' => 1234567890,
            'key' => 'test',
        ], $lib->timezone('10.1, 20.2', 1234567890));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLatLng(): void
    {
        $lib = new Services\Timezone(new ApiAuth('test'));
        $this->assertEquals([
            'timestamp' => 1234567,
            'location' => '10.70000000,11.40000000',
            'key' => 'test',
        ], $lib->timezone([10.7, 11.4], 1234567));
        $this->assertEquals([
            'timestamp' => 1234567,
            'location' => '20.50000000,22.80000000',
            'key' => 'test',
        ], $lib->timezone(['lat' => 20.5, 'lng' => 22.8], 1234567));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\Timezone(new ApiAuth('test'));
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(ServiceException::class);
        $lib->timezone(['foo' => 'bar']);
    }
}

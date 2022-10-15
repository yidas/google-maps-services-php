<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class TimezoneTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\Timezone();
        $this->assertEquals('/maps/api/timezone/json', $lib->getPath());
        $this->assertEquals([
            'location' => '10.1, 20.2',
            'timestamp' => 1234567890,
        ], $lib->timezone('10.1, 20.2', 1234567890));
    }

    /**
     * @throws Exception
     */
    public function testServiceLatLng(): void
    {
        $lib = new Services\Timezone();
        $this->assertEquals([
            'timestamp' => 1234567,
            'location' => '10.70000000,11.40000000',
        ], $lib->timezone([10.7, 11.4], 1234567));
        $this->assertEquals([
            'timestamp' => 1234567,
            'location' => '20.50000000,22.80000000',
        ], $lib->timezone(['lat' => 20.5, 'lng' => 22.8], 1234567));
    }

    /**
     * @throws Exception
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\Timezone();
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(Exception::class);
        $lib->timezone(['foo' => 'bar']);
    }
}

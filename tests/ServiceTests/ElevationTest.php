<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class ElevationTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\Elevation();
        $this->assertEquals('/maps/api/elevation/json', $lib->getPath());
        $this->assertEquals([
            'locations' => 'you do not know where',
        ], $lib->elevation('you do not know where'));
    }

    /**
     * @throws Exception
     */
    public function testServiceLatLng(): void
    {
        $lib = new Services\Elevation();
        $this->assertEquals([
            'locations' => '10.70000000,11.40000000',
        ], $lib->elevation([10.7, 11.4]));
        $this->assertEquals([
            'locations' => '20.50000000,22.80000000',
        ], $lib->elevation(['lat' => 20.5, 'lng' => 22.8]));
    }

    /**
     * @throws Exception
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\Elevation();
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(Exception::class);
        $lib->elevation(['foo' => 'bar']);
    }
}

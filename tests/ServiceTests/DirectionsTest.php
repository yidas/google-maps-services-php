<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class DirectionsTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\Directions();
        $this->assertEquals('/maps/api/directions/json', $lib->getPath());
        $this->assertEquals([
            'origin' => 'you do not know where',
            'destination' => 'you do not want to know',
        ], $lib->directions('you do not know where', 'you do not want to know'));
    }
}

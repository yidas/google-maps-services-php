<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class DistanceTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\DistanceMatrix();
        $this->assertEquals('/maps/api/distancematrix/json', $lib->getPath());
        $this->assertEquals([
            'origins' => 'you do not know where',
            'destinations' => 'you do not want to know',
        ], $lib->distanceMatrix('you do not know where', 'you do not want to know'));
    }
}

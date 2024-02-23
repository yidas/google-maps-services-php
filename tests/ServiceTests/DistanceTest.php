<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\GoogleMaps\ApiAuth;
use yidas\GoogleMaps\ServiceException;
use yidas\GoogleMaps\Services;


class DistanceTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\DistanceMatrix(new ApiAuth('test'));
        $this->assertEquals('/maps/api/distancematrix/json', $lib->getPath());
        $this->assertEquals([
            'origins' => 'you do not know where',
            'destinations' => 'you do not want to know',
            'key' => 'test',
        ], $lib->distanceMatrix('you do not know where', 'you do not want to know'));
    }
}

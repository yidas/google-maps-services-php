<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class DistanceTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\DistanceMatrix(new ApiAuth('test'));
        $this->assertEquals('https://maps.googleapis.com/maps/api/distancematrix/json', $lib->getPath());
        $this->assertEquals([
            'origins' => 'you do not know where',
            'destinations' => 'you do not want to know',
            'key' => 'test',
        ], $lib->distanceMatrix('you do not know where', 'you do not want to know'));
    }
}

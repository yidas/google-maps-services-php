<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class RoadsTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\Roads(new ApiAuth('test'));
        $this->assertEquals('https://roads.googleapis.com/v1/snapToRoads', $lib->getPath());
        $this->assertEquals([
            'path' => 'foo',
            'interpolate' => 'true',
            'key' => 'test',
        ], $lib->snapToRoads('foo', ['interpolate' => true]));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFields(): void
    {
        $lib = new Services\Roads(new ApiAuth('test'));
        $this->assertEquals([
            'path' => '123.456,789,123|456.789,123.456|789.123,456,789',
            'key' => 'test',
        ], $lib->snapToRoads([[123.456, 789,123], [456.789, 123.456], [789.123, 456,789]], ['interpolate' => false]));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongPath(): void
    {
        $lib = new Services\Roads(new ApiAuth('test'));
        $this->expectExceptionMessage('Unknown path format. Pass array of arrays of floats or the string itself.');
        $this->expectException(ServiceException::class);
        $lib->snapToRoads(123456789, ['foo' => 'bar']);
    }
}

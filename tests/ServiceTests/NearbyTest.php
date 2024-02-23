<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\GoogleMaps\ApiAuth;
use yidas\GoogleMaps\ServiceException;
use yidas\GoogleMaps\Services;


class NearbyTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\Nearby(new ApiAuth('test'));
        $this->assertEquals('/maps/api/place/nearbysearch/json', $lib->getPath());
        $this->assertEquals([
            'keyword' => 'foo',
            'radius' => 10.2,
            'type' => 'bar',
            'key' => 'test',
        ], $lib->nearby('foo', [], 10.2, 'bar'));
    }

    /**
     * @param array<string, string> $result
     * @param array<string|int, string> $latLng
     * @throws ServiceException
     * @dataProvider serviceLatLngProvider
     */
    public function testServiceLatLng(array $result, array $latLng): void
    {
        $lib = new Services\Nearby(new ApiAuth('test'));
        $this->assertEquals($result, $lib->nearby('', $latLng));
    }

    public function serviceLatLngProvider(): array
    {
        return [
            [['latlng' => '10.70000000,11.40000000', 'key' => 'test',], [10.7, 11.4]],
            [['latlng' => '20.50000000,22.80000000', 'key' => 'test',], ['lat' => 20.5, 'lng' => 22.8]],
        ];
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new Services\Nearby(new ApiAuth('test'));
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(ServiceException::class);
        $lib->nearby('', []);
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\Nearby(new ApiAuth('test'));
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(ServiceException::class);
        $lib->nearby('', ['foo' => 'bar']);
    }
}

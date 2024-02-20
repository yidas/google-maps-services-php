<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class NearbyTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\Nearby();
        $this->assertEquals('/maps/api/place/nearbysearch/json', $lib->getPath());
        $this->assertEquals([
            'keyword' => 'foo',
            'radius' => 10.2,
            'type' => 'bar',
        ], $lib->nearby('foo', [], 10.2, 'bar'));
    }

    /**
     * @param array<string, string> $result
     * @param array<string|int, string> $latLng
     * @throws Exception
     * @dataProvider serviceLatLngProvider
     */
    public function testServiceLatLng(array $result, array $latLng): void
    {
        $lib = new Services\Nearby();
        $this->assertEquals($result, $lib->nearby('', $latLng));
    }

    public function serviceLatLngProvider(): array
    {
        return [
            [['latlng' => '10.70000000,11.40000000',], [10.7, 11.4]],
            [['latlng' => '20.50000000,22.80000000',], ['lat' => 20.5, 'lng' => 22.8]],
        ];
    }

    /**
     * @throws Exception
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new Services\Nearby();
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(Exception::class);
        $lib->nearby('', []);
    }

    /**
     * @throws Exception
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\Nearby();
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(Exception::class);
        $lib->nearby('', ['foo' => 'bar']);
    }
}

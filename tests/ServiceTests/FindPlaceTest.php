<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class FindPlaceTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\FindPlace(new ApiAuth('test'));
        $this->assertEquals('https://maps.googleapis.com/maps/api/place/findplacefromtext/json', $lib->getPath());
        $this->assertEquals([
            'input' => 'foo',
            'inputtype' => 'bar',
            'key' => 'test',
        ], $lib->findPlace('foo', 'bar'));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFields(): void
    {
        $lib = new Services\FindPlace(new ApiAuth('test'));
        $this->assertEquals([
            'input' => 'foo',
            'inputtype' => 'bar',
            'fields' => 'photo,rating',
            'key' => 'test',
        ], $lib->findPlace('foo', 'bar', ['rating', 'witchcraft', 'photo', 'issues']));
    }

    /**
     * @param array<string, string> $result
     * @param array<string|int, string> $bias
     * @throws ServiceException
     * @dataProvider biasProvider
     */
    public function testServiceBias(array $result, array $bias): void
    {
        $lib = new Services\FindPlace(new ApiAuth('test'));
        $this->assertEquals($result, $lib->findPlace('foo', 'bar', [], $bias));
    }

    public function biasProvider(): array
    {
        return [
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'ipbias', 'key' => 'test',], []],
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'circle:70.00@10.700000,11.400000', 'key' => 'test',], [10.7, 11.4, 70]],
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'circle:6.30@20.500000,22.800000', 'key' => 'test',], ['lat' => 20.5, 'lng' => 22.8, 'rad' => 6.3]],
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'rectangle:10.700000,11.400000|-9.700000,-3.200000', 'key' => 'test',], [10.7, 11.4, -9.7, -3.2]],
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'rectangle:15.000000,-5.100000|20.500000,22.800000', 'key' => 'test',], ['n' => 20.5, 'e' => 22.8, 's' => 15.0, 'w' => -5.1, ]],
        ];
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new Services\FindPlace(new ApiAuth('test'));
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(ServiceException::class);
        $lib->findPlace('', 'foo');
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\FindPlace(new ApiAuth('test'));
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(ServiceException::class);
        $lib->findPlace('foo', 'bar', [], ['foo' => 'bar']);
    }
}

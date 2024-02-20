<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class FindPlaceTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\FindPlace();
        $this->assertEquals('/maps/api/place/findplacefromtext/json', $lib->getPath());
        $this->assertEquals([
            'input' => 'foo',
            'inputtype' => 'bar',
        ], $lib->findPlace('foo', 'bar'));
    }

    /**
     * @throws Exception
     */
    public function testServiceFields(): void
    {
        $lib = new Services\FindPlace();
        $this->assertEquals([
            'input' => 'foo',
            'inputtype' => 'bar',
            'fields' => 'photo,rating',
        ], $lib->findPlace('foo', 'bar', ['rating', 'witchcraft', 'photo', 'issues']));
    }

    /**
     * @param array<string, string> $result
     * @param array<string|int, string> $bias
     * @throws Exception
     * @dataProvider biasProvider
     */
    public function testServiceBias(array $result, array $bias): void
    {
        $lib = new Services\FindPlace();
        $this->assertEquals($result, $lib->findPlace('foo', 'bar', [], $bias));
    }

    public function biasProvider(): array
    {
        return [
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'ipbias',], []],
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'circle:70.00@10.700000,11.400000',], [10.7, 11.4, 70]],
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'circle:6.30@20.500000,22.800000',], ['lat' => 20.5, 'lng' => 22.8, 'rad' => 6.3]],
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'rectangle:10.700000,11.400000|-9.700000,-3.200000',], [10.7, 11.4, -9.7, -3.2]],
            [['input' => 'foo', 'inputtype' => 'bar', 'locationbias' => 'rectangle:15.000000,-5.100000|20.500000,22.800000',], ['n' => 20.5, 'e' => 22.8, 's' => 15.0, 'w' => -5.1, ]],
        ];
    }

    /**
     * @throws Exception
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new Services\FindPlace();
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(Exception::class);
        $lib->findPlace('', 'foo');
    }

    /**
     * @throws Exception
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new Services\FindPlace();
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(Exception::class);
        $lib->findPlace('foo', 'bar', [], ['foo' => 'bar']);
    }
}

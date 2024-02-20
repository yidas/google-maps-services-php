<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class FindTextTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\FindText();
        $this->assertEquals('/maps/api/place/textsearch/json', $lib->getPath());
        $this->assertEquals([
            'query' => 'foo',
            'radius' => '5.00',
            'maxprice' => 8,
            'minprice' => -3,
            'opennow' => 'true',
            'region' => 'it',
            'type' => 'stadium',
        ], $lib->findText('foo', 5, [], 8, -3, true, 'Italy', 'stadium'));
    }

    /**
     * @param array<string, string> $result
     * @param float<0, 50000> $radius
     * @param array<string|int, string> $location
     * @throws Exception
     * @dataProvider biasProvider
     */
    public function testServiceBias(array $result, float $radius, array $location): void
    {
        $lib = new Services\FindText();
        $this->assertEquals($result, $lib->findText('foo', $radius, $location));
    }

    public function biasProvider(): array
    {
        return [
            [['query' => 'foo', 'radius' => '70.00', 'location' => '10.70000000,11.40000000',], 70, [10.7, 11.4]],
            [['query' => 'foo', 'radius' => '6.30', 'location' => '20.50000000,22.80000000',], 6.3, ['lat' => 20.5, 'lng' => 22.8]],
        ];
    }

    /**
     * @throws Exception
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new Services\FindText();
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(Exception::class);
        $lib->findText('', 51);
    }

    /**
     * @throws Exception
     */
    public function testServiceFailWrongCoords(): void
    {
        $lib = new Services\FindText();
        $this->expectExceptionMessage('Passed invalid values into coordinates! You must use either array with lat and lng and rad or 0, 1, 2 and 3 keys.');
        $this->expectException(Exception::class);
        $lib->findText('foo', 5, ['bar' => 'baz']);
    }
}

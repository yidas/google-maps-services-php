<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class PlaceDetailsTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testService(): void
    {
        $lib = new Services\PlaceDetails();
        $this->assertEquals('/maps/api/place/details/json', $lib->getPath());
        $this->assertEquals([
            'place_id' => 'foo',
            'region' => 'gr',
            'reviews_sort' => 'newest',
            'reviews_no_translations' => 'true',
        ], $lib->placeDetails('foo', [], 'Greece', false, 'Newest'));
    }

    /**
     * @throws Exception
     */
    public function testServiceFields(): void
    {
        $lib = new Services\PlaceDetails();
        $this->assertEquals([
            'place_id' => 'foo',
            'fields' => 'photo,rating',
        ], $lib->placeDetails('foo', ['rating', 'witchcraft', 'photo', 'issues']));
    }

    /**
     * @throws Exception
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new Services\PlaceDetails();
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(Exception::class);
        $lib->placeDetails('');
    }
}

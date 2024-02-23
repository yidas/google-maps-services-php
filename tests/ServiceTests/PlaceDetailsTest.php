<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\GoogleMaps\ApiAuth;
use yidas\GoogleMaps\ServiceException;
use yidas\GoogleMaps\Services;


class PlaceDetailsTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\PlaceDetails(new ApiAuth('test'));
        $this->assertEquals('/maps/api/place/details/json', $lib->getPath());
        $this->assertEquals([
            'place_id' => 'foo',
            'region' => 'gr',
            'reviews_sort' => 'newest',
            'reviews_no_translations' => 'true',
            'key' => 'test',
        ], $lib->placeDetails('foo', [], 'Greece', false, 'Newest'));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFields(): void
    {
        $lib = new Services\PlaceDetails(new ApiAuth('test'));
        $this->assertEquals([
            'place_id' => 'foo',
            'fields' => 'photo,rating',
            'key' => 'test',
        ], $lib->placeDetails('foo', ['rating', 'witchcraft', 'photo', 'issues']));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new Services\PlaceDetails(new ApiAuth('test'));
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(ServiceException::class);
        $lib->placeDetails('');
    }
}

<?php

namespace ServiceTests;


use CommonTestClass;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class RoutesTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\Routes(new ApiAuth('test'));
        $this->assertEquals('https://routes.googleapis.com/directions/v2:computeRoutes', $lib->getPath());
        $this->assertEquals('POST', $lib->getMethod());
        $this->assertEquals(null, $lib->getBody());
        $this->assertEquals([
            'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.legs,geocodingResults',
            'X-Goog-Api-Key' => 'test',
        ], $lib->getHeaders());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceHeaderFail(): void
    {
        $lib = new Services\Routes(new ApiAuth(['clientID' => 'foo', 'clientSecret' => 'bar']));
        $this->expectExceptionMessage('No correct API key set!');
        $this->expectException(ServiceException::class);
        $lib->getHeaders();
    }

    /**
     * @throws ServiceException
     */
    public function testServiceRoute(): void
    {
        $lib = new Services\Routes(new ApiAuth('test'));
        $this->assertEquals([], $lib->route([10.7, 11.4, 'foo' => 'bar',], []));
        $this->assertEquals('{"origin":{"0":10.7,"1":11.4,"foo":"bar"},"destination":[]}', $lib->getBody());

        $lib->setLanguage('hk');
        $this->assertEquals([], $lib->route(['foo' => 'bar',], [10.7, 11.4,]));
        $this->assertEquals('{"origin":{"foo":"bar"},"destination":[10.7,11.4],"languageCode":"hk"}', $lib->getBody());
    }
}

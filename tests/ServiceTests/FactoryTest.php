<?php

namespace ServiceTests;


use CommonTestClass;
use ReflectionException;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class FactoryTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function testServiceOk(): void
    {
        $lib = new XFactory(new ApiAuth('test'));
        $this->assertNotEmpty($lib->getService('directions'));
    }

    /**
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new XFactory(new ApiAuth('test'));
        $this->expectExceptionMessage('Call to undefined service method *unknown one*');
        $this->expectException(ServiceException::class);
        $lib->getService('unknown one');
    }

    /**
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new XFactory(new ApiAuth('test'));
        $this->expectExceptionMessage('Service *ServiceTests\XClass* is not an instance of \yidas\GoogleMaps\Services\AbstractService');
        $this->expectException(ServiceException::class);
        $lib->getService('unusable');
    }
}


class XFactory extends Services\ServiceFactory
{
    protected $serviceMethodMap = [
        'directions' => Services\Directions::class,
        'unusable' => XClass::class,
    ];
}


class XClass extends \stdClass
{
    public function __construct($param)
    {
    }
}


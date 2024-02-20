<?php

namespace ServiceTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\Services;


class FactoryTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testServiceOk(): void
    {
        $lib = new XFactory();
        $this->assertNotEmpty($lib->getService('directions'));
    }

    /**
     * @throws Exception
     */
    public function testServiceFailNoTarget(): void
    {
        $lib = new XFactory();
        $this->expectExceptionMessage('Call to undefined service method *unknown one*');
        $this->expectException(Exception::class);
        $lib->getService('unknown one');
    }

    /**
     * @throws Exception
     */
    public function testServiceFailWrongTarget(): void
    {
        $lib = new XFactory();
        $this->expectExceptionMessage('Service *\stdClass* is not an instance of \yidas\GoogleMaps\Services\AbstractService');
        $this->expectException(Exception::class);
        $lib->getService('unusable');
    }
}


class XFactory extends Services\ServiceFactory
{
    protected $serviceMethodMap = [
        'directions' => Services\Directions::class,
        'unusable' => '\stdClass',
    ];
}

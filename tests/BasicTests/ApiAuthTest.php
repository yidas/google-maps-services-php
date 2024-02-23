<?php

namespace BasicTests;


use CommonTestClass;
use yidas\GoogleMaps\ApiAuth;
use yidas\GoogleMaps\ServiceException;


class ApiAuthTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testServicePassStringKey(): void
    {
        $lib = new ApiAuth('wanabe key');
        $this->assertEquals([
            'key' => 'wanabe key',
        ], $lib->getAuthParams());
    }

    /**
     * @throws ServiceException
     */
    public function testServicePassArrayKey(): void
    {
        $lib = new ApiAuth(['key' => 'wanabe key']);
        $this->assertEquals([
            'key' => 'wanabe key',
        ], $lib->getAuthParams());
    }

    /**
     * @throws ServiceException
     */
    public function testServicePassCredentials(): void
    {
        $lib = new ApiAuth(['clientID' => 'wanabe key', 'clientSecret' => 'wanabe secret']);
        $this->assertEquals([
            'client' => 'wanabe key',
            'signature' => 'wanabe secret',
        ], $lib->getAuthParams());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailNoKey(): void
    {
        $this->expectExceptionMessage('Unable to set Client credential due to your wrong params');
        $this->expectException(ServiceException::class);
        new ApiAuth([]);
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailTooManyKeys(): void
    {
        $this->expectExceptionMessage('clientID/clientSecret should not set when using key');
        $this->expectException(ServiceException::class);
        new ApiAuth(['key' => 'wanabe key', 'clientID' => 'wanabe id']);
    }
}

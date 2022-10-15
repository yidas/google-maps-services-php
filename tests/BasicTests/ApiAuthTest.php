<?php

namespace BasicTests;


use CommonTestClass;
use Exception;
use yidas\GoogleMaps\ApiAuth;


class ApiAuthTest extends CommonTestClass
{
    /**
     * @throws Exception
     */
    public function testServicePassStringKey(): void
    {
        $lib = new ApiAuth('wanabe key');
        $this->assertEquals([
            'key' => 'wanabe key',
        ], $lib->getAuthParams());
    }

    /**
     * @throws Exception
     */
    public function testServicePassArrayKey(): void
    {
        $lib = new ApiAuth(['key' => 'wanabe key']);
        $this->assertEquals([
            'key' => 'wanabe key',
        ], $lib->getAuthParams());
    }

    /**
     * @throws Exception
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
     * @throws Exception
     */
    public function testServiceFailNoKey(): void
    {
        $this->expectExceptionMessage('Unable to set Client credential due to your wrong params');
        $this->expectException(Exception::class);
        new ApiAuth([]);
    }

    /**
     * @throws Exception
     */
    public function testServiceFailTooManyKeys(): void
    {
        $this->expectExceptionMessage('clientID/clientSecret should not set when using key');
        $this->expectException(Exception::class);
        new ApiAuth(['key' => 'wanabe key', 'clientID' => 'wanabe id']);
    }
}

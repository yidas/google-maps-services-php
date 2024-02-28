<?php

namespace BasicTests;


use CommonTestClass;
use yidas\googleMaps\ApiAuth;
use yidas\googleMaps\Clients;
use yidas\googleMaps\ServiceException;
use yidas\googleMaps\Services;


class ServiceTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testServiceFailDecode(): void
    {
        $lib = new Services(new XTestClient1(), new Services\ServiceFactory(new ApiAuth('test')));
        $lib->setLanguage('Hr-hr');
        $this->assertEquals('dummy response from remote service', $lib->directions('foo', 'bar'));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailCode(): void
    {
        $lib = new Services(new XTestClient2(), new Services\ServiceFactory(new ApiAuth('test')));
        $this->assertEquals([
            'error' => 'Unable to show',
        ], $lib->directions('foo', 'bar'));
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailMessage(): void
    {
        $lib = new Services(new XTestClient3(), new Services\ServiceFactory(new ApiAuth('test')));
        $this->assertEquals([
            'error_message' => 'Unable to show',
        ], $lib->directions('foo', 'bar'));
    }

    /**
     * @throws ServiceException
     */
    public function testServicePassWithoutResults(): void
    {
        $lib = new Services(new XTestClient4(), new Services\ServiceFactory(new ApiAuth('test')));
        $this->assertEquals([
            'status' => 'Show now',
        ], $lib->directions('foo', 'bar'));
    }

    /**
     * @throws ServiceException
     */
    public function testServicePassWithResults(): void
    {
        $lib = new Services(new XTestClient5(), new Services\ServiceFactory(new ApiAuth('test')));
        $this->assertEquals([
            'a',
            'b',
        ], $lib->directions('foo', 'bar'));
    }
}


class XTestClient1 extends Clients\AbstractClient
{
    public function request(string $apiPath, array $query = [], string $method = 'GET', array $headers = [], ?string $body = null): Clients\AbstractResponse
    {
        return new XTestResponse1();
    }
}


class XTestResponse1 extends Clients\AbstractResponse
{
    public function getStatusCode(): int
    {
        return 200;
    }

    public function getMessageBody(): string
    {
        return 'dummy response from remote service';
    }
}


class XTestClient2 extends Clients\AbstractClient
{
    public function request(string $apiPath, array $query = [], string $method = 'GET', array $headers = [], ?string $body = null): Clients\AbstractResponse
    {
        return new XTestResponse2();
    }
}


class XTestResponse2 extends Clients\AbstractResponse
{
    public function getStatusCode(): int
    {
        return 400;
    }

    public function getMessageBody(): string
    {
        return '{"error": "Unable to show"}';
    }
}


class XTestClient3 extends Clients\AbstractClient
{
    public function request(string $apiPath, array $query = [], string $method = 'GET', array $headers = [], ?string $body = null): Clients\AbstractResponse
    {
        return new XTestResponse3();
    }
}


class XTestResponse3 extends Clients\AbstractResponse
{
    public function getStatusCode(): int
    {
        return 200;
    }

    public function getMessageBody(): string
    {
        return '{"error_message": "Unable to show"}';
    }
}


class XTestClient4 extends Clients\AbstractClient
{
    public function request(string $apiPath, array $query = [], string $method = 'GET', array $headers = [], ?string $body = null): Clients\AbstractResponse
    {
        return new XTestResponse4();
    }
}


class XTestResponse4 extends Clients\AbstractResponse
{
    public function getStatusCode(): int
    {
        return 200;
    }

    public function getMessageBody(): string
    {
        return '{"status": "Show now"}';
    }
}


class XTestClient5 extends Clients\AbstractClient
{
    public function request(string $apiPath, array $query = [], string $method = 'GET', array $headers = [], ?string $body = null): Clients\AbstractResponse
    {
        return new XTestResponse5();
    }
}


class XTestResponse5 extends Clients\AbstractResponse
{
    public function getStatusCode(): int
    {
        return 200;
    }

    public function getMessageBody(): string
    {
        return '{"results": ["a", "b"]}';
    }
}

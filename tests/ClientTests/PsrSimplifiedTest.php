<?php

namespace ClientTests;


use RuntimeException;
use yidas\googleMaps\Clients\Psr;
use yidas\googleMaps\ServiceException;


class PsrSimplifiedTest extends \CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testUriPass(): void
    {
        $lib = new Psr\SimplifiedUri('hop://localhost:332/absolute/address?happens#here');
        $this->assertEquals('hop', $lib->getScheme());
        $this->assertEquals('localhost:332', $lib->getAuthority());
        $this->assertEquals('', $lib->getUserInfo());
        $this->assertEquals('localhost', $lib->getHost());
        $this->assertEquals('332', $lib->getPort());
        $this->assertEquals('/absolute/address', $lib->getPath());
        $this->assertEquals('happens', $lib->getQuery());
        $this->assertEquals('here', $lib->getFragment());

        $lib->withScheme('');
        $this->assertEquals('', $lib->getScheme());
        $lib->withUserInfo('foo', 'bar');
        $this->assertEquals('foo:bar', $lib->getUserInfo());
        $lib->withUserInfo('baz');
        $this->assertEquals('baz', $lib->getUserInfo());
        $lib->withHost('');
        $this->assertEquals('', $lib->getHost());
        $lib->withPort(0);
        $this->assertEquals(0, $lib->getPort());
        $lib->withPort(null);
        $this->assertEquals('', $lib->getPort());
        $lib->withPath('/nowhere');
        $this->assertEquals('/nowhere', $lib->getPath());
        $lib->withQuery('foo=bar');
        $this->assertEquals('foo=bar', $lib->getQuery());
        $lib->withFragment('unknown');
        $this->assertEquals('unknown', $lib->getFragment());
    }

    /**
     * @throws ServiceException
     */
    public function testUriFail(): void
    {
        $this->expectExceptionMessage('Link parser got something strange.');
        $this->expectException(ServiceException::class);
        new Psr\SimplifiedUri('/relative/address/happens');
    }

    public function testBodyPass(): void
    {
        $lib = new Psr\SimplifiedBody('Test content');
        $this->assertEquals('Test content', strval($lib));
        $this->assertEquals('Test content', $lib->getContents());
        $this->assertNull($lib->detach());
        $this->assertEquals(12, $lib->getSize());
        $this->assertFalse($lib->eof());
        $this->assertFalse($lib->isSeekable());
        $this->assertFalse($lib->isWritable());
        $this->assertFalse($lib->isReadable());
        $this->assertNull($lib->getMetadata('something'));
        $this->assertEquals([], $lib->getMetadata());
        $lib->close();
    }

    /**
     * @throws RuntimeException
     */
    public function testBodyFail1(): void
    {
        $lib = new Psr\SimplifiedBody('Ignore me');
        $this->expectExceptionMessage('Not supported');
        $this->expectException(RuntimeException::class);
        $lib->tell();
    }

    /**
     * @throws RuntimeException
     */
    public function testBodyFail2(): void
    {
        $lib = new Psr\SimplifiedBody('Ignore me');
        $this->expectExceptionMessage('Not supported');
        $this->expectException(RuntimeException::class);
        $lib->seek(0);
    }

    /**
     * @throws RuntimeException
     */
    public function testBodyFail3(): void
    {
        $lib = new Psr\SimplifiedBody('Ignore me');
        $this->expectExceptionMessage('Not supported');
        $this->expectException(RuntimeException::class);
        $lib->rewind();
    }

    /**
     * @throws RuntimeException
     */
    public function testBodyFail4(): void
    {
        $lib = new Psr\SimplifiedBody('Ignore me');
        $this->expectExceptionMessage('Not supported');
        $this->expectException(RuntimeException::class);
        $lib->write('ignore');
    }

    /**
     * @throws RuntimeException
     */
    public function testBodyFail5(): void
    {
        $lib = new Psr\SimplifiedBody('Ignore me');
        $this->expectExceptionMessage('Not supported');
        $this->expectException(RuntimeException::class);
        $lib->read(0);
    }

    public function testRequestPass(): void
    {
        $lib = new Psr\SimplifiedRequest(new Psr\SimplifiedUri('test://localhost.test/there'), new Psr\SimplifiedBody('to_test'));
        $this->assertEquals('1.0', $lib->getProtocolVersion());
        $this->assertEquals([], $lib->getHeaders());
        $this->assertEquals('to_test', strval($lib->getBody()));
        $this->assertEquals('/there', $lib->getRequestTarget());
        $this->assertEquals('get', $lib->getMethod());
        $this->assertEquals('/there', $lib->getUri()->getPath());

        $lib->withProtocolVersion('0.1');
        $this->assertEquals('0.1', $lib->getProtocolVersion());
        $lib->withBody(new Psr\SimplifiedBody('other_value'));
        $this->assertEquals('other_value', strval($lib->getBody()));
        $lib->withRequestTarget('wont_be_used');
        $this->assertEquals('/there', $lib->getRequestTarget());
        $lib->withMethod('');
        $this->assertEquals('', $lib->getMethod());
        $lib->withUri(new Psr\SimplifiedUri('test://localhost.test/other/data/somewhere'));
        $this->assertEquals('/other/data/somewhere', $lib->getUri()->getPath());
    }

    public function testRequestHeaders(): void
    {
        $lib = new Psr\SimplifiedRequest(new Psr\SimplifiedUri('test://localhost.test/there'), new Psr\SimplifiedBody('to_test'));
        $this->assertEquals([], $lib->getHeaders());
        $this->assertFalse($lib->hasHeader('Foo'));
        $this->assertEquals([], $lib->getHeader('Foo'));
        $this->assertEquals('', $lib->getHeaderLine('Foo'));
        $lib->withHeader('foo', 'bar');
        $lib->withHeader('fOO', 'baz');
        $this->assertEquals(['fOO' => ['baz']], $lib->getHeaders());
        $lib->withoutHeader('FOo');
        $lib->withAddedHeader('foo', 'bar');
        $lib->withAddedHeader('foo', 'baz');
        $this->assertEquals(['foo' => ['bar', 'baz']], $lib->getHeaders());
        $this->assertTrue($lib->hasHeader('Foo'));
        $this->assertEquals(['bar', 'baz'], $lib->getHeader('FoO'));
        $this->assertEquals('bar,baz', $lib->getHeaderLine('FoO'));
    }
}

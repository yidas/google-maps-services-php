<?php

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use yidas\googleMaps\Clients\PhpResponse;


/**
 * Class CommonTestClass
 * The structure for mocking and configuration seems so complicated, but it's necessary to let it be totally idiot-proof
 */
class CommonTestClass extends TestCase
{
}


class XHttp implements ResponseInterface
{
    protected $phpResponseMiddle = null;

    public function __construct(string $in)
    {
        $this->phpResponseMiddle = new PhpResponse($in);
    }

    public function getProtocolVersion()
    {
        throw new \Exception('mock');
    }

    public function withProtocolVersion($version)
    {
        throw new \Exception('mock');
    }

    public function getHeaders()
    {
        throw new \Exception('mock');
    }

    public function hasHeader($name)
    {
        throw new \Exception('mock');
    }

    public function getHeader($name)
    {
        throw new \Exception('mock');
    }

    public function getHeaderLine($name)
    {
        throw new \Exception('mock');
    }

    public function withHeader($name, $value)
    {
        throw new \Exception('mock');
    }

    public function withAddedHeader($name, $value)
    {
        throw new \Exception('mock');
    }

    public function withoutHeader($name)
    {
        throw new \Exception('mock');
    }

    public function getBody()
    {
        return new \XStream($this->phpResponseMiddle->getMessageBody());
    }

    public function withBody(StreamInterface $body)
    {
        throw new \Exception('mock');
    }

    public function getStatusCode()
    {
        return $this->phpResponseMiddle->getStatusCode();
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        throw new \Exception('mock');
    }

    public function getReasonPhrase()
    {
        throw new \Exception('mock');
    }
}


class XStream implements StreamInterface
{
    protected $data = '';

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return $this->data;
    }

    public function close()
    {
        throw new \Exception('mock');
    }

    public function detach()
    {
        throw new \Exception('mock');
    }

    public function getSize()
    {
        return strlen($this->data);
    }

    public function tell()
    {
        throw new \Exception('mock');
    }

    public function eof()
    {
        throw new \Exception('mock');
    }

    public function isSeekable()
    {
        throw new \Exception('mock');
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        throw new \Exception('mock');
    }

    public function rewind()
    {
        throw new \Exception('mock');
    }

    public function isWritable()
    {
        throw new \Exception('mock');
    }

    public function write($string)
    {
        throw new \Exception('mock');
    }

    public function isReadable()
    {
        throw new \Exception('mock');
    }

    public function read($length)
    {
        throw new \Exception('mock');
    }

    public function getContents()
    {
        return $this->data;
    }

    public function getMetadata($key = null)
    {
        throw new \Exception('mock');
    }
}

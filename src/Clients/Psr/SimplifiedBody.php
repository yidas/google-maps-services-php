<?php

namespace yidas\googleMaps\Clients\Psr;


use RuntimeException;
use Psr\Http\Message\StreamInterface;


class SimplifiedBody implements StreamInterface
{
    /**
     * @var string
     */
    protected $currentData = '';

    public function __construct(string $data)
    {
        $this->currentData = $data;
    }

    public function __toString()
    {
        return $this->currentData;
    }

    public function close()
    {
    }

    public function detach()
    {
        return null;
    }

    public function getSize()
    {
        return strlen($this->currentData);
    }

    public function tell()
    {
        throw new RuntimeException('Not supported');
    }

    public function eof()
    {
        return false;
    }

    public function isSeekable()
    {
        return false;
    }

    /**
     * @param int $offset
     * @param int $whence
     * @return void
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        throw new RuntimeException('Not supported');
    }

    /**
     * @return void
     */
    public function rewind()
    {
        throw new RuntimeException('Not supported');
    }

    public function isWritable()
    {
        return false;
    }

    public function write($string)
    {
        throw new RuntimeException('Not supported');
    }

    public function isReadable()
    {
        return false;
    }

    public function read($length)
    {
        throw new RuntimeException('Not supported');
    }

    public function getContents()
    {
        return $this->currentData;
    }

    public function getMetadata($key = null)
    {
        return is_null($key) ? [] : null;
    }
}

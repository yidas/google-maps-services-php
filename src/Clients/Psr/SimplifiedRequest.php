<?php

namespace yidas\googleMaps\Clients\Psr;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;


class SimplifiedRequest implements RequestInterface
{
    /**
     * @var string
     */
    protected $protocolVersion = '1.0';

    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var UriInterface
     */
    protected $uri = null;

    /**
     * @var array<string, array<int, string>>
     */
    protected $headers = [];

    /**
     * @var StreamInterface
     */
    protected $body = null;

    public function __construct(UriInterface $uri, StreamInterface $body)
    {
        $this->uri = $uri;
        $this->body = $body;
    }

    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version)
    {
        $this->protocolVersion = $version;
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        foreach ($this->headers as $key => $header) {
            if (strtolower($key) == strtolower($name)) {
                return true;
            }
        }
        return false;
    }

    public function getHeader($name)
    {
        foreach ($this->headers as $key => $header) {
            if (strtolower($key) == strtolower($name)) {
                return $header;
            }
        }
        return [];
    }

    public function getHeaderLine($name)
    {
        foreach ($this->headers as $key => $header) {
            if (strtolower($key) == strtolower($name)) {
                return implode(',', $header);
            }
        }
        return '';
    }

    public function withHeader($name, $value)
    {
        return $this->withoutHeader($name)->withAddedHeader($name, $value);
    }

    public function withAddedHeader($name, $value)
    {
        $added = false;
        foreach ($this->headers as $key => $header) {
            if (strtolower($key) == strtolower($name)) {
                $this->headers[$name] = array_merge($header, (array) $value);
                $added = true;
                break;
            }
        }
        if (!$added) {
            $this->headers[$name] = (array) $value;
        }
        return $this;
    }

    public function withoutHeader($name)
    {
        foreach ($this->headers as $key => $header) {
            if (strtolower($key) == strtolower($name)) {
                unset($this->headers[$key]);
            }
        }
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        $this->body = $body;
        return $this;
    }

    public function getRequestTarget()
    {
        $uri = $this->getUri();
        $path     = !empty($uri->getPath()) ? $uri->getPath() : '/';
        $query    = !empty($uri->getQuery()) ? '?' . $uri->getQuery() : '';
        return "$path$query";
    }

    public function withRequestTarget($requestTarget)
    {
        // NOPE, set URI object!
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $this->uri = $uri;
        return $this;
    }
}

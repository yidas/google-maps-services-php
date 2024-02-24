<?php

namespace yidas\GoogleMaps\Clients\Psr;


use Psr\Http\Message\UriInterface;
use yidas\GoogleMaps\ServiceException;


class SimplifiedUri implements UriInterface
{
    /**
     * @var array<string, string|int|null>
     */
    protected $parsedData = [
        'scheme' => '',
        'host' => '',
        'port' => null,
        'user' => null,
        'pass' => null,
        'path' => '',
        'query' => '',
        'fragment' => '',
    ];

    /**
     * @param string $data
     * @throws ServiceException
     */
    public function __construct(string $data)
    {
        $parsedLink = parse_url($data);
        if ((false === $parsedLink) || empty($parsedLink["host"]) || empty($parsedLink['path'])) {
            // to get this error you must pass unrecognizable address into parser
            throw new ServiceException('Link parser got something strange.');
        }

        $this->parsedData = array_merge($this->parsedData, $parsedLink);
    }

    public function __toString()
    {
        $scheme   = !empty($this->parsedData['scheme']) ? $this->parsedData['scheme'] . '://' : '';
        $host     = !empty($this->parsedData['host']) ? $this->parsedData['host'] : '';
        $port     = !empty($this->parsedData['port']) ? ':' . $this->parsedData['port'] : '';
        $user     = !empty($this->parsedData['user']) ? $this->parsedData['user'] : '';
        $pass     = !empty($this->parsedData['pass']) ? ':' . $this->parsedData['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = !empty($this->parsedData['path']) ? $this->parsedData['path'] : '';
        $query    = !empty($this->parsedData['query']) ? '?' . $this->parsedData['query'] : '';
        $fragment = !empty($this->parsedData['fragment']) ? '#' . $this->parsedData['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    public function getScheme()
    {
        return strval($this->parsedData['scheme']);
    }

    public function getAuthority()
    {
        $userInfo = $this->getUserInfo();
        $port = $this->getPort();
        return
            (empty($userInfo) ? '' : $userInfo . '@')
            . $this->getHost()
            . (empty($port) ? '' : ':' . $port)
        ;
    }

    public function getUserInfo()
    {
        return is_null($this->parsedData['user'])
            ? ''
            : $this->parsedData['user'] . (
                is_null($this->parsedData['pass'])
                    ? ''
                    : ':' . $this->parsedData['pass']
            );
    }

    public function getHost()
    {
        return strval($this->parsedData['host']);
    }

    public function getPort()
    {
        return is_null($this->parsedData['port'])
            ? null
            : intval($this->parsedData['port'])
        ;
    }

    public function getPath()
    {
        return strval($this->parsedData['path']);
    }

    public function getQuery()
    {
        return strval($this->parsedData['query']);
    }

    public function getFragment()
    {
        return strval($this->parsedData['fragment']);
    }

    public function withScheme($scheme)
    {
        $this->parsedData['scheme'] = $scheme;
        return $this;
    }

    public function withUserInfo($user, $password = null)
    {
        $this->parsedData['user'] = $user;
        $this->parsedData['pass'] = $password;
        return $this;
    }

    public function withHost($host)
    {
        $this->parsedData['host'] = $host;
        return $this;
    }

    public function withPort($port)
    {
        $this->parsedData['port'] = $port;
        return $this;
    }

    public function withPath($path)
    {
        $this->parsedData['path'] = $path;
        return $this;
    }

    public function withQuery($query)
    {
        $this->parsedData['query'] = $query;
        return $this;
    }

    public function withFragment($fragment)
    {
        $this->parsedData['fragment'] = $fragment;
        return $this;
    }
}

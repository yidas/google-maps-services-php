<?php

namespace yidas\googleMaps\Clients\Kw;


use kalanis\RemoteRequest\Protocols;


/**
 * Query for kalanis\RemoteRequest
 * Version which allows to pass already known data in body
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.1.0
 */
class Query extends Protocols\Http\Query
{
    /**
     * @param array<string, string> $headers
     * @return Query
     */
    public function addHeaders(array $headers): self
    {
        foreach ($headers as $key => $value) {
            $this->addHeader(strval($key), strval($value));
        }
        return $this;
    }

    public function isInline(): bool
    {
        return false;
    }

    public function isMultipart(): bool
    {
        return false;
    }

    protected function getSimpleRequest(): string
    {
        return $this->body;
    }

    protected function contentTypeHeader(): parent
    {
        if (empty($this->body)) {
            // other services
            $this->removeHeader('Content-Type');
        } else {
            // geolocation and other services with request body
            $this->addHeader('Content-Type', 'application/json');
        }
        return $this;
    }
}

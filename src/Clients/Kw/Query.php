<?php

namespace yidas\GoogleMaps\Clients\Kw;


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
            $this->removeHeader('Content-Type');
        } else {
            $this->addHeader('Content-Type', 'application/json');
        }
        return $this;
    }
}

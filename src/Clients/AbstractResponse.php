<?php

namespace yidas\GoogleMaps\Clients;

/**
 * Google Maps PHP Client - abstract response class for usage different HTTP implementations
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.0.0
 **/
abstract class AbstractResponse
{

    /**
     * Get status code of http request
     *
     * @return int
     */
    abstract public function getStatusCode(): int;

    /**
     * Get body of http request
     *
     * @return string
     */
    abstract public function getMessageBody(): string;
}

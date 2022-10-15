<?php

namespace yidas\GoogleMaps\Services;

/**
 * Google Maps Abstract Service
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 */
abstract class AbstractService
{

    /**
     * Return path to query endpoint
     *
     * @return string
     */
    abstract public function getPath(): string;

    /**
     * Return method which will be used in query
     *
     * @return string
     */
    public function getMethod(): string
    {
        return 'GET';
    }

    /**
     * Return prepared body if there is any
     *
     * @return string
     */
    public function getBody(): ?string
    {
        return null;
    }

    /**
     * If too parse inner response body for 'results' node
     * Usually necessary to discard that behavior if you want to get 'status' node
     *
     * @return bool
     */
    public function wantInnerResult(): bool
    {
        return true;
    }
}

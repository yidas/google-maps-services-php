<?php

namespace yidas\GoogleMaps\Services;

use yidas\GoogleMaps\ApiAuth;

/**
 * Google Maps Abstract Service
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 *
 * Each basic call returns params to GET part of request
 * To fill HTTP headers, you must fill method "getHeaders()"
 * To fill request body, you must data in "$body" variable
 *
 * Pass ApiAuth class is necessary to set Google API keys
 */
abstract class AbstractService
{

    /**
     * Google Maps default language
     *
     * @var string|null ex. 'zh-TW'
     */
    protected $language;

    /**
     * Google Api Auth class providing auth params
     *
     * @var ApiAuth
     */
    protected $auth;

    /**
     * @var string|null body of the message
     */
    protected $body = null;

    /**
     * Constructor
     *
     * @param ApiAuth $auth Google Api class to get auth params
     */
    public function __construct(ApiAuth $auth)
    {
        $this->auth = $auth;
    }

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
     * Return prepared headers if there are some
     *
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return [];
    }

    /**
     * Return prepared body if there is any
     *
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->body;
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

    /**
     * Set default language for Google Maps API
     *
     * @param string|null $language ex. 'zh-TW'
     * @return self
     */
    public function setLanguage(?string $language = null): self
    {
        $this->language = $language;
        return $this;
    }

    protected function canAddLanguage(string $method): bool
    {
        return ('GET' == strtoupper($method)) && $this->language;
    }
}

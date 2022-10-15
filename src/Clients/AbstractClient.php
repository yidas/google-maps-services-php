<?php

namespace yidas\GoogleMaps\Clients;

/**
 * Google Maps PHP Client - abstract client for usage different HTTP implementations
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.0.0
 **/
abstract class AbstractClient
{
    /**
     * Google Maps default language
     *
     * @var string|null ex. 'zh-TW'
     */
    protected $language;

    /**
     * Request Google Map API
     *
     * @param string $apiPath
     * @param array<string, string|int|float> $params
     * @param string $method HTTP request method
     * @param string $body
     * @return AbstractResponse
     */
    abstract public function request(string $apiPath, array $params = [], string $method = 'GET', ?string $body = null): AbstractResponse;

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
}

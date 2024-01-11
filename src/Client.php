<?php

namespace yidas\GoogleMaps;

use Exception;

/**
 * Google Maps PHP Client - facade for processing
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @version 1.0.0
 * 
 * @method array directions(string $origin, string $destination, array $params=[])
 * @method array distanceMatrix(string $origin, string $destination, array $params=[])
 * @method array elevation(string $locations, array $params=[])
 * @method array geocode(string $address, array $params=[])
 * @method array reverseGeocode($latlng, array $params=[])
 * @method array geolocate(array $bodyParams=[])
 * @method array timezone(string $location, string $timestamp=null, array $params=[])
 * @method array nearby(string $keyword, float[] $latlng, float $radius=null, string $type=null, array $params=[])
 *
 * @codeCoverageIgnore because accessing external resources
 */
class Client
{
    /**
     * Available service to run
     *
     * @var Services
     */
    protected $services;

    /**
     * Constructor
     *
     * @param string|array<string, string> $optParams API Key or option parameters
     *  'key' => Google API Key
     *  'clientID' => Google clientID
     *  'clientSecret' => Google clientSecret
     * @throws Exception
     */
    public function __construct($optParams)
    {
        $this->services = new Services(new Clients\GuzzleClient(new ApiAuth($optParams)), new Services\ServiceFactory());
        $defaultLang = isset($optParams['language']) ? $optParams['language'] : null;
        if ($defaultLang) {
            $this->setLanguage(strval($defaultLang));
        }
    }

    /**
     * Set default language for Google Maps API
     *
     * @param string|null $language ex. 'zh-TW'
     * @return $this
     */
    public function setLanguage(?string $language=null): self
    {
        $this->services->setLanguage($language);
        return $this;
    }

    /**
     * Client methods refer to each service
     * 
     * All service methods from Client calling would leave out the first argument (Client itself).
     *
     * @param string $method Client's method name
     * @param array<int, string|int|float> $arguments Method arguments
     * @throws Exception
     * @return mixed Current service method return
     */
    public function __call($method, $arguments)
    {
        return $this->services->__call($method, $arguments);
    }
}

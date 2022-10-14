<?php

namespace yidas\GoogleMaps;

use Exception;

/**
 * Google Maps PHP Client - access services
 * 
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.0.0
 * 
 * @method array directions(string $origin, string $destination, array $params=[]) 
 * @method array distanceMatrix(string $origin, string $destination, array $params=[]) 
 * @method array elevation(string $locations, array $params=[]) 
 * @method array geocode(string $address, array $params=[]) 
 * @method array reverseGeocode($latlng, array $params=[])
 * @method array geolocate(array $bodyParams=[])
 * @method array timezone(string $location, string $timestamp=null, array $params=[])
 * @method array nearby(string $keyword, float[] $latlng, float $radius=null, string $type=null, array $params=[])
 */
class Services
{

    /**
     * Http client which will ask for data
     *
     * @var Clients\AbstractClient
     */
    protected $client;

    /**
     * Available services passed through factory
     *
     * @var Services\ServiceFactory
     */
    protected $factory;

    /**
     * Constructor
     *
     * @param Clients\AbstractClient $client
     * @param Services\ServiceFactory|null $factory to get correct services
     * @throws Exception
     */
    public function __construct(Clients\AbstractClient $client, ?Services\ServiceFactory $factory = null)
    {
        $this->client = $client;
        $this->factory = $factory ?: new Services\ServiceFactory();
    }

    /**
     * Set default language for Google Maps API
     *
     * @param string $language ex. 'zh-TW'
     * @return $this
     */
    public function setLanguage(?string $language=null): self
    {
        $this->client->setLanguage($language);
        return $this;
    }

    /**
     * Client methods refer to each service
     * 
     * All service methods from Client calling would leave out the first argument (Client itself).
     *
     * @param string Client's method name
     * @param array Method arguments
     * @throws Exception
     * @return mixed Processed service method return
     */
    public function __call($method, $arguments)
    {
        // walkthrough:
        // 1 - get service
        // 2 - call method to create query; pass params there
        // 3 - method asks client for http data
        // 4 - client response to method
        // 5 - method parse response and returns them to the user

        // Get service from Factory
        $service = $this->factory->getService($this->client, $method);

        return call_user_func_array([$service, $method], $arguments);
    }
}

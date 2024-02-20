<?php

namespace yidas\GoogleMaps;

use Exception;

/**
 * Google Maps PHP Client - access services, process calls
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
 * @method array findPlace(string $input, string $inputType, string[] $fields=[], float[] $bias=null, array $params=[])
 * @method array findText(string $query, float $radius, float[] $location=[], int $maxPrice=null, int $minPrice=null, bool $openNow=false, string $region=null, string $type=null, array $params=[])
 * @method array placeDetails(string $placeId, string[] $fields=[], string $region=null, bool $translateReviews=true, string $sortReviews=null, array $params=[])
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
     * @param string $method Client's method name
     * @param array<int, string|int|float> $arguments Method arguments
     * @throws Exception
     * @return mixed Processed service method return
     */
    public function __call($method, $arguments)
    {
        // walkthrough:
        // 1 - get service
        // 2 - call method to create query; pass params there
        // 3 - asks client for http data with things from correct service
        // 4 - client response with something
        // 5 - parse response and returns it to the user

        // Get service from Factory
        $service = $this->factory->getService($method);

        $params = (array) call_user_func_array([$service, $method], $arguments);

        $response = $this->client->request($service->getPath(), $params, $service->getMethod(), $service->getBody());
        $message = $response->getMessageBody();
        $result = json_decode($message, true);

        // Error Handler
        if (empty($result) && !empty($message)) {
            // Error message directly in content
            return $message;
        } elseif (200 !== $response->getStatusCode()) {
            // status code passed something
            return $result;
        }
        $result = (array) $result;
        if (isset($result['error_message'])) {
            // Error message Checker (200 situation from Google Maps API)
            return $result;
        }

        // `results` parsing from Google Maps API, while pass parsing on error
        return isset($result['results']) && $service->wantInnerResult() ? $result['results'] : $result;
    }
}

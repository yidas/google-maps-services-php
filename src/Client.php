<?php

namespace yidas\googleMaps;

use Exception;
use GuzzleHttp\Client as HttpClient;

/**
 * Google Maps PHP Client
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @version 1.0.0
 * @method array directions(string $origin,string $destination,array $params = []) $origin and $destination can be of any type as long as they cast to a string consumable by the google api
 * @method array distanceMatrix(string|array $origin, string|array $destination,array $params = []) if $origin or $destination are arrays, they will be converted to strings separated by a pipe (|)
 * @method array elevation(string|array $locations, array $options = []) $locations can be a string in the format of 'lat,lon' or 'lat1,lon1|lat2,lon2|...'
 *                                                                       If an array of scalar values, it will assume it's [lat,lon]. Any additional elements will be ignored
 *                                                                       If an array of arrays, it will assume it's [[lat1,lon1],[lat2,lon2],...]
 * @method array geocode(string $address, array $params = []) $address can be of any type that will cast to a string consumable by the google apis
 * @method array reverseGeocode(array|string $location, $params = []) If $location is an array, it will be assumed to be [lat, lon]. If a string that appears to represent a lat, lon, it will be treated as such. Otherwise it will be treated as a place id.
 * @method array timezone(array|string $location, string|int|\DateTime $timestamp = null, $params = []) $location can be "lat,lon" string or [lat,lon] array. If $timestamp is a non-numeric string, it will be parsed by \DateTime. If that fails, it will use the value of time(). If it is numeric, it will be used as-is.
 *
 */
class Client
{
    /**
     * Google Maps Platform base API host
     */
    const API_HOST = 'https://maps.googleapis.com';

    /**
     * For service autoload
     * 
     * @see http://php.net/manual/en/language.namespaces.rules.php
     */
    const SERVICE_NAMESPACE = "\\yidas\\googleMaps\\";

    /**
     * For Client-Service API method director
     *
     * @var array Method => Service Class name
     */
    protected static $serviceMethodMap = [
        'directions' => 'Directions',
        'distanceMatrix' => 'DistanceMatrix',
        'elevation' => 'Elevation',
        'geocode' => 'Geocoding',
        'reverseGeocode' => 'Geocoding',
        'geolocate' => 'Geolocation',
        'timezone' => 'Timezone',
    ];
    
    /**
     * Google API Key
     * 
     * Authenticating by API Key, otherwise by client ID/digital signature
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Google client ID
     *
     * @var string
     */
    protected $clientID;

    /**
     * Google client's digital signature
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * GuzzleHttp\Client
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Google Maps default language
     *
     * @var string ex. 'zh-TW'
     */
    protected $language;
    
    /**
     * Constructor
     *
     * @param string|array $optParams API Key or option parameters
     *  'key' => Google API Key
     *  'clientID' => Google clientID
     *  'clientSecret' => Google clientSecret
     * @return self
     */
    function __construct($optParams) 
    {
        // Quick setting for API Key
        if (is_string($optParams)) {
            // Params as a string key
            $key = $optParams;
            $optParams = [];
            $optParams['key'] = $key;
        }

        // Assignment
        $key = isset($optParams['key']) ? $optParams['key'] : null;
        $clientID = isset($optParams['clientID']) ? $optParams['clientID'] : null;
        $clientSecret = isset($optParams['clientSecret']) ? $optParams['clientSecret'] : null;
        $defaultLang = isset($optParams['language']) ? $optParams['language'] : null;

        // Use API Key
        if ($key) {

            if ($clientID || $clientSecret) {
                throw new Exception("clientID/clientSecret should not set when using key", 400);
            }
            
            $this->apiKey = (string) $key;
        }
        // Use clientID/clientSecret
        elseif ($clientID && $clientSecret) {
            
            $this->clientID = (string) $clientID;
            $this->clientSecret = (string) $clientSecret;
        } 
        else {

            throw new Exception("Unable to set Client credential due to your wrong params", 400);
        }

        // Default Language setting
        if ($defaultLang) {
            $this->setLanguage($defaultLang);
        }

        // Load GuzzleHttp\Client
        $this->httpClient = new HttpClient([
            'base_uri' => self::API_HOST,
            'timeout'  => 5.0,
        ]);

        return $this;
    }

    /**
     * Request Google Map API
     *
     * @param string $apiPath
     * @param array $params
     * @param string $method HTTP request method
     * @param string $body
     * @return GuzzleHttp\Psr7\Response
     */
    public function request($apiPath, $params=[], $method='GET', $body=null)
    {   
        // Guzzle request options
        $options = [
            'http_errors' => false,
        ];

        // Parameters for Auth
        $defaultParams = ($this->apiKey) 
            ? ['key' => $this->apiKey] 
            : ['client' => $this->clientID, 'signature' => $this->clientSecret];
        // Parameters for Language setting
        if ($this->language) {
            $defaultParams['language'] = $this->language;
        }

        // Query
        $options['query'] = array_merge($defaultParams, $params);

        // Body
        if ($body) {
            $options['body'] = $body;
        }
        
        return $this->httpClient->request($method, $apiPath, $options);
    }

    /**
     * Set default language for Google Maps API
     *
     * @param string $language ex. 'zh-TW'
     * @return self
     */
    public function setLanguage($language=null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Client methods refer to each service
     * 
     * All service methods from Client calling would leave out the first argument (Client itself).
     *
     * @param string Client's method name
     * @param array Method arguments
     * @return mixed Current service method return
     * @example 
     *  $equal = \yidas\googleMaps\Geocoding::geocode($client, 'Address');
     *  $equal = $client->geocode('Address');
     */
    public function __call($method, $arguments)
    {
        // Matching self::$serviceMethodMap is required
        if (!isset(self::$serviceMethodMap[$method])) 
            throw new Exception("Call to undefined method ".__CLASS__."::{$method}()", 400);
        
        // Get the service mapped by method
        $service = self::$serviceMethodMap[$method];

        // Fill Client in front of arguments
        array_unshift($arguments, $this);

        return call_user_func_array([self::SERVICE_NAMESPACE . $service, $method], $arguments);
    }
}

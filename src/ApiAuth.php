<?php

namespace yidas\GoogleMaps;

/**
 * Google Maps PHP Client - API auth params
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @version 2.0.0
 */
class ApiAuth
{
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
     * Constructor
     *
     * @param string|array<string, string> $optParams API Key or option parameters
     *  'key' => Google API Key
     *  'clientID' => Google clientID
     *  'clientSecret' => Google clientSecret
     * @throws ServiceException
     */
    public function __construct($optParams)
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

        // Use API Key
        if ($key) {

            if ($clientID || $clientSecret) {
                throw new ServiceException("clientID/clientSecret should not set when using key", 400);
            }

            $this->apiKey = (string) $key;
        } // Use clientID/clientSecret
        elseif ($clientID && $clientSecret) {

            $this->clientID = (string) $clientID;
            $this->clientSecret = (string) $clientSecret;
        } else {

            throw new ServiceException("Unable to set Client credential due to your wrong params", 400);
        }
    }

    /**
     * @return array<string, string>
     */
    public function getAuthParams(): array
    {
        return ($this->apiKey)
            ? ['key' => $this->apiKey]
            : ['client' => $this->clientID, 'signature' => $this->clientSecret];
    }
}

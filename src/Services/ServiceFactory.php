<?php

namespace yidas\GoogleMaps\Services;

use Exception;
use yidas\GoogleMaps\Clients\AbstractClient;

/**
 * Google Maps PHP Client - factory to get services
 * 
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.0.0
 **/
class ServiceFactory
{
    /**
     * For Client-Service API method director
     *
     * @var array<string, string> Method => Service Class name
     */
    protected static $serviceMethodMap = [
        'directions' => Directions::class,
        'distanceMatrix' => DistanceMatrix::class,
        'elevation' => Elevation::class,
        'geocode' => Geocoding::class,
        'reverseGeocode' => Geocoding::class,
        'geolocate' => Geolocation::class,
        'timezone' => Timezone::class,
        'nearby' => Nearby::class,
    ];

    /**
     * @param AbstractClient $client
     * @param string $method
     * @throws Exception
     * @return AbstractService
     */
    public function getService(AbstractClient $client, string $method): AbstractService
    {
        // Matching self::$serviceMethodMap is required
        if (!isset(self::$serviceMethodMap[$method])) {
            throw new Exception("Call to undefined service method *{$method}*", 400);
        }

        // Get the service mapped by method
        $service = self::$serviceMethodMap[$method];

        return new $service($client);
    }
}

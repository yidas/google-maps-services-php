<?php

namespace yidas\GoogleMaps\Services;

use ReflectionClass;
use ReflectionException;
use yidas\GoogleMaps\ApiAuth;
use yidas\GoogleMaps\ServiceException;

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
     * @var array<string, class-string> Method => Service Class name
     */
    protected $serviceMethodMap = [
        'directions' => Directions::class,
        'distanceMatrix' => DistanceMatrix::class,
        'elevation' => Elevation::class,
        'geocode' => Geocoding::class,
        'reverseGeocode' => Geocoding::class,
        'geolocate' => Geolocation::class,
        'timezone' => Timezone::class,
        'nearby' => Nearby::class,
        'findPlace' => FindPlace::class,
        'findText' => FindText::class,
        'placeDetails' => PlaceDetails::class,
        'route' => Routes::class,
    ];

    /**
     * @var ApiAuth
     */
    protected $apiAuth = null;

    public function __construct(ApiAuth $apiAuth)
    {
        $this->apiAuth = $apiAuth;
    }

    /**
     * @param string $method
     * @throws ReflectionException
     * @throws ServiceException
     * @return AbstractService
     */
    public function getService(string $method): AbstractService
    {
        // Matching self::$serviceMethodMap is required
        if (!isset($this->serviceMethodMap[$method])) {
            throw new ServiceException("Call to undefined service method *{$method}*", 400);
        }

        // Get the service mapped by method
        $service = $this->serviceMethodMap[$method];

        $reflection = new ReflectionClass($service);
        $instance = $reflection->newInstance($this->apiAuth);

        if (!$instance instanceof AbstractService) {
            throw new ServiceException("Service *{$service}* is not an instance of \yidas\GoogleMaps\Services\AbstractService", 400);
        }

        return $instance;
    }
}

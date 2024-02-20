<?php

namespace yidas\GoogleMaps\Services;

use Exception;
use ReflectionClass;

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
    ];

    /**
     * @param string $method
     * @throws Exception
     * @return AbstractService
     */
    public function getService(string $method): AbstractService
    {
        // Matching self::$serviceMethodMap is required
        if (!isset($this->serviceMethodMap[$method])) {
            throw new Exception("Call to undefined service method *{$method}*", 400);
        }

        // Get the service mapped by method
        $service = $this->serviceMethodMap[$method];

        $reflection = new ReflectionClass($service);
        $instance = $reflection->newInstance();

        if (!$instance instanceof AbstractService) {
            throw new Exception("Service *{$service}* is not an instance of \yidas\GoogleMaps\Services\AbstractService", 400);
        }

        return $instance;
    }
}

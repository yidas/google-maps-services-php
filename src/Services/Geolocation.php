<?php

namespace yidas\GoogleMaps\Services;

use yidas\GoogleMaps\ServiceException;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see     https://developers.google.com/maps/documentation/geolocation/
 * @see     https://developers.google.com/maps/documentation/geolocation/requests-geolocation
 */
class Geolocation extends AbstractService
{
    public function getPath(): string
    {
        return 'https://www.googleapis.com/geolocation/v1/geolocate';
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * Geolocate
     *
     * @param array<mixed> $bodyParams Body parameters
     * @throws ServiceException
     * @return array<string, string|int|float>
     */
    public function geolocate(array $bodyParams=[]): array
    {
        // Google API request body format
        $body = json_encode($bodyParams, JSON_UNESCAPED_SLASHES);
        if (false === $body) {
            // @codeCoverageIgnoreStart
            // to get this error you must have something really fishy in $bodyParams
            throw new ServiceException(json_last_error_msg());
        }
        // @codeCoverageIgnoreEnd
        $this->body = $body;
        return $this->auth->getAuthParams();
    }
}

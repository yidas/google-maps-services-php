<?php

namespace yidas\GoogleMaps\Services;

use LogicException;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/geolocation/
 */
class Geolocation extends AbstractService
{
    /**
     * @var string|null body of the message
     */
    protected $body;

    public function getPath(): string
    {
        return 'https://www.googleapis.com/geolocation/v1/geolocate';
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Geolocate
     *
     * @param array<mixed> $bodyParams Body parameters
     * @throws LogicException
     * @return array<string, string|int|float>
     */
    public function geolocate($bodyParams=[]): array
    {
        // Google API request body format
        $body = json_encode($bodyParams);
        if (false === $body) {
            // @codeCoverageIgnoreStart
            // to get this error you must have something really fishy in $bodyParams
            throw new LogicException(json_last_error_msg());
        }
        // @codeCoverageIgnoreEnd
        $this->body = $body;
        return [];
    }
}

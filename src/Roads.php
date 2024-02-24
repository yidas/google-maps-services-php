<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Roads Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/roads
 */
class Roads extends Service
{
    const API_URL = 'https://roads.googleapis.com/v1/snapToRoads';

    const LANGUAGE_METHOD = false;

    /**
     * Reverse Geocode
     *
     * @param Client $client
     * @param string $address
     * @param array Query parameters
     * @return array Result
     */
    public static function snapToRoads(Client $client, $path=null, $params=[])
    {
        if (is_array($path)) {
            $pathString = '';
            foreach ($path as $key => $eachPathArray) {
                $pathString = ($key != 0) ? $pathString . '|' : $pathString;
                $pathString .= implode(',', $eachPathArray);
            }
            $params['path'] = $pathString;
        }  

        $params['interpolate'] = $params['interpolate'] ?? 'true';

        return self::requestHandler($client, self::API_URL, $params);
    }
}

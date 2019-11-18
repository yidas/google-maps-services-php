<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/timezone/
 */
class Timezone extends Service
{
    const API_PATH = '/maps/api/timezone/json';

    /**
     * Timezone
     *
     * @param Client $client
     * @param string|array $location
     * @param string|int|\DateTime $timestamp
     * @param array $params
     * @return array Result
     */
    public static function timezone(Client $client, $location, $timestamp=null, $params=[])
    {
        // `location` seems to only allow `lat,lng` pattern
        if (is_string($location)) {
            
            $params['location'] = $location;

        } else {

            list($lat, $lng) = $location;
            $params['location'] = "{$lat},{$lng}";
        }

				if($timestamp instanceof \DateTime){
					$timestamp = $timestamp->getTimestamp();
				} elseif (null !== $timestamp && is_scalar($timestamp) && !is_numeric($timestamp)){
					try {
						$dt = new \DateTime($timestamp);
						$timestamp = $dt->getTimestamp();
					} catch (\Throwable $t){
						$timestamp = time();
					}
				} elseif(null === $timestamp || !is_scalar($timestamp) || !is_numeric($timestamp)){
					$timestamp = time();
				}  //else we know it's scalar and numeric, so use it as-is


        $params['timestamp'] = intval($timestamp);

        return self::requestHandler($client, self::API_PATH, $params);
    }
}

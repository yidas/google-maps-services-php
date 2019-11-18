<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/distance-matrix/
 */
class DistanceMatrix extends Service
{
    const API_PATH = '/maps/api/distancematrix/json';

    /**
     * Distance matrix
     *
     * @param Client $client
     * @param string|array $origins
     * @param string|array $destinations
     * @param array $params
     * @return array Result
     */
    public static function distanceMatrix(Client $client, $origins, $destinations, $params=[])
    {
    	  if(is_array($origins)){
    	  	$origins = implode("|",$origins);
	      }

    	  if(is_array($destinations)){
    	  	$destinations = implode("|",$destinations);
	      }

        $params['origins'] = (string) $origins;
        $params['destinations'] = (string) $destinations;

        return self::requestHandler($client, self::API_PATH, $params);
    }
}

<?php

namespace yidas\googleMaps;

use yidas\googleMaps\Service;
use yidas\googleMaps\Client;

/**
 * Directions Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.0.0
 * @see https://developers.google.com/maps/documentation/elevation/
 */
class Elevation extends Service
{
    const API_PATH = '/maps/api/elevation/json';

    /**
     * Elevation
     *
     * @param Client $client
     * @param string|array $locations - can be a string in the format of 'lat,lon' or 'lat1,lon1|lat2,lon2|...'
     *                                If an array of scalar values, it will assume it's [lat,lon]. Any additional elements will be ignored
     *                                If an array of arrays, it will assume it's [[lat1,lon1],[lat2,lon2],...]
     *
     * @param array $params
     *
     * @return array Result
     */
    public static function elevation(Client $client, $locations, $params=[])
    {
        // `locations` seems to only allow `lat,lng` pattern
        if (is_string($locations)) {
            
            $params['locations'] = $locations;

        } else {
        	  $locations = array_values($locations);
						if(is_array($locations) && is_scalar($locations[0])){
							//assume we just have a single lat,long in an array/
							$params['locations'] = "{$locations[0]},{$locations[1]}";
						} else {

							$locations = array_map(
								function($a) {
									return implode(",", $a);
								},
								$locations
							);
							$params['locations'] = implode("|",$locations);
						}

        }

        return self::requestHandler($client, self::API_PATH, $params);
    }
}

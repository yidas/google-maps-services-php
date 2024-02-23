<?php

namespace yidas\GoogleMaps\Services;

use yidas\GoogleMaps\ServiceException;

/**
 * Find by text service
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @since   2.2.0
 * @see     https://developers.google.com/maps/documentation/places/web-service/search-text
 */
class FindText extends AbstractMapService
{
    public function getPath(): string
    {
        return '/maps/api/place/textsearch/json';
    }

    /**
     * Find by text lookup
     *
     * @param string $query what will be searched
     * @param float $radius in which radius
     * @param array<string|int, float> $location ['lat', 'lng', 'rad']
     * @param int<0,4>|null $maxPrice
     * @param int<0,4>|null $minPrice
     * @param bool $openNow
     * @param string|null $region
     * @param string|null $type
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return array<string, string|int|float>
     */
    public function findText(
        string $query,
        float $radius,
        array $location = [],
        ?int $maxPrice = null,
        ?int $minPrice = null,
        bool $openNow = false,
        ?string $region = null,
        ?string $type = null,
        array $params=[]
    ): array
    {
        if (empty($query) || empty($radius)) {
            throw new ServiceException('You must set where to look!');
        }

        // Main wanted name
        $params['query'] = $query;
        $params['radius'] = sprintf('%1.02F', $radius);

        // `location` seems to only allow `lat,lng` pattern
        if (!empty($location)) {

            if (isset($location['lat']) && isset($location['lng'])) {

                $params['location'] = sprintf('%1.08F,%1.08F', $location['lat'], $location['lng']);

            } elseif (isset($location[0]) && isset($location[1])) {

                $params['location'] = sprintf('%1.08F,%1.08F', $location[0], $location[1]);

            } else {

                throw new ServiceException('Passed invalid values into coordinates! You must use either array with lat and lng and rad or 0, 1, 2 and 3 keys.');

            }
        }

        if (!empty($maxPrice)) {
            $params['maxprice'] = $maxPrice;
        }

        if (!empty($minPrice)) {
            $params['minprice'] = $minPrice;
        }

        if ($openNow) {
            $params['opennow'] = 'true';
        }

        if (!empty($region)) {
            $params['region'] = strtolower(substr($region, 0,2));
        }

        if (!empty($type)) {
            $params['type'] = $type;
        }

        return $this->extendQueryParams($params);
    }
}

<?php

namespace yidas\googleMaps\Services;

use yidas\googleMaps\ServiceException;

/**
 * Find by Place service
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @since   2.2.0
 * @see     https://developers.google.com/maps/documentation/places/web-service/search-find-place
 */
class FindPlace extends AbstractMapService
{
    /**
     * @var string[]
     */
    protected $allowedFields = [
        // basic
        'address_components', 'adr_address', 'business_status', 'formatted_address', 'geometry', 'icon',
        'icon_mask_base_uri', 'icon_background_color', 'name', 'photo', 'place_id', 'plus_code', 'type',
        'url', 'utc_offset', 'vicinity', 'wheelchair_accessible_entrance',
        // contact
        'current_opening_hours', 'formatted_phone_number', 'international_phone_number', 'opening_hours',
        'secondary_opening_hours', 'website',
        // atmosphere
        'curbside_pickup', 'delivery', 'dine_in', 'editorial_summary', 'price_level', 'rating', 'reservable',
        'reviews', 'serves_beer', 'serves_breakfast', 'serves_brunch', 'serves_dinner', 'serves_lunch',
        'serves_vegetarian_food', 'serves_wine', 'takeout', 'user_ratings_total',
    ];

    public function getPath(): string
    {
        return static::API_HOST . '/maps/api/place/findplacefromtext/json';
    }

    /**
     * Find Place lookup
     *
     * @param string $input what will be searched
     * @param string $inputType what kind of data will be get
     * @param string[] $fields which fields you want to get
     * @param array<string|int, float>|null $bias ['lat', 'lng', 'rad']
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return array<string, string|int|float>
     */
    public function findPlace(
        string $input,
        string $inputType,
        array $fields = [],
        ?array $bias = null,
        array $params=[]
    ): array
    {
        if (empty($input) || empty($inputType)) {
            throw new ServiceException('You must set where to look!');
        }

        // Main wanted name
        $params['input'] = $input;
        $params['inputtype'] = $inputType;
        if (!empty($fields)) {
            $params['fields'] = implode(',', array_intersect($this->allowedFields, $fields));
        }

        // `locationbias` seems to only allow `lat,lng,rad` pattern
        if (!empty($bias)) {

            if (isset($bias['lat']) && isset($bias['lng']) && isset($bias['rad'])) {

                $params['locationbias'] = sprintf('circle:%1.02F@%1.06F,%1.06F', $bias['rad'], $bias['lat'], $bias['lng']);

            } elseif (isset($bias['n']) && isset($bias['s']) && isset($bias['e']) && isset($bias['w'])) {

                $params['locationbias'] = sprintf('rectangle:%1.06F,%1.06F|%1.06F,%1.06F', $bias['s'], $bias['w'], $bias['n'], $bias['e']);

            } elseif (isset($bias[0]) && isset($bias[1]) && isset($bias[2]) && isset($bias[3])) {

                $params['locationbias'] = sprintf('rectangle:%1.06F,%1.06F|%1.06F,%1.06F', $bias[0], $bias[1], $bias[2], $bias[3]);

            } elseif (isset($bias[0]) && isset($bias[1]) && isset($bias[2])) {

                $params['locationbias'] = sprintf('circle:%1.02F@%1.06F,%1.06F', $bias[2], $bias[0], $bias[1]);

            } else {

                throw new ServiceException('Passed invalid values into coordinates! You must use either array with lat and lng and rad or 0, 1, 2 and 3 keys.');

            }
        } elseif (!is_null($bias)) {

            $params['locationbias'] = 'ipbias';

        }

        return $this->extendQueryParams($params);
    }
}

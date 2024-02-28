<?php

namespace yidas\googleMaps\Services;


use yidas\googleMaps\ServiceException;

/**
 * Roads Service
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @since   1.2.0
 * @see https://developers.google.com/maps/documentation/roads
 */
class Roads extends AbstractService
{
    public function getPath(): string
    {
        return 'https://roads.googleapis.com/v1/snapToRoads';
    }

    /**
     * Roads lookup
     * @param array<int, float[]>|string|null $path
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return array<string, string|int|float>
     */
    public function snapToRoads($path=null, $params=[])
    {
        if (is_array($path)) {
            $positions = [];
            foreach ($path as $key => $eachPathArray) {
                $positions[] = implode(',', $eachPathArray);
            }
            $params['path'] = implode('|', $positions);
        } elseif (is_string($path)) {
            $params['path'] = $path;
        } else {
            throw new ServiceException('Unknown path format. Pass array of arrays of floats or the string itself.');
        }

        if (isset($params['interpolate'])) {
            if ($params['interpolate']) {
                $params['interpolate'] = 'true';
            } else {
                unset($params['interpolate']);
            }
        }

        return array_merge($this->auth->getAuthParams(), $params);
    }
}

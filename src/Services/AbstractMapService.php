<?php

namespace yidas\GoogleMaps\Services;

/**
 * Google Maps Abstract Service
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @since   3.0.0
 *
 * Map service query params extension
 */
abstract class AbstractMapService extends AbstractService
{
    const API_HOST = 'https://maps.googleapis.com';

    /**
     * @param array<string, string|int|float> $params
     * @return array<string, string|int|float>
     */
    protected function extendQueryParams(array $params): array
    {
        return array_merge($this->auth->getAuthParams(), $this->getLanguageForQuery(), $params);
    }

    /**
     * @return array<string, string>
     */
    protected function getLanguageForQuery(): array
    {
        $params = [];
        if ($this->canAddLanguage($this->getMethod()) && !empty($this->language)) {
            $params['language'] = $this->language;
        }
        return $params;
    }
}

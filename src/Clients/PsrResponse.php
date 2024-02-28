<?php

namespace yidas\googleMaps\Clients;

use Psr\Http\Message\ResponseInterface;

/**
 * Google Maps Abstract Service - response in processable format
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.0.0
 */
class PsrResponse extends AbstractResponse
{
    /**
     * Response by PSR-7
     *
     * @var ResponseInterface
     */
    protected $response = null;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getMessageBody(): string
    {
        return $this->response->getBody()->getContents();
    }
}

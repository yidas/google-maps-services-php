<?php

namespace yidas\GoogleMaps\Clients;

use kalanis\RemoteRequest\Protocols\Http\Answer;

/**
 * Google Maps Abstract Service - response in processable format
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.1.0
 *
 * Underlying library is RemoteRequest
 */
class KwResponse extends AbstractResponse
{
    /** @var Answer */
    protected $answer = null;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    public function getStatusCode(): int
    {
        return $this->answer->getCode();
    }

    public function getMessageBody(): string
    {
        return $this->answer->getContent();
    }
}

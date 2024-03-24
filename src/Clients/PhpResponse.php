<?php

namespace yidas\googleMaps\Clients;


/**
 * Google Maps Abstract Service - response in processable format
 *
 * @author  Petr Plsek <me@kalanys.com>
 * @version 2.1.0
 *
 * For more use kalanis\RemoteRequest or other libraries specially designed for manipulation with HTTP response
 */
class PhpResponse extends AbstractResponse
{
    const DELIMITER = "\r\n";

    /** @var int */
    protected $code = 0;
    /** @var string */
    protected $reason = '';
    /** @var string */
    protected $body = '';

    public function __construct(string $data)
    {
        $this->processStringResponse($data);
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getMessageBody(): string
    {
        return $this->body;
    }

    protected function processStringResponse(string $data): void
    {
        if (false !== strpos($data, self::DELIMITER . self::DELIMITER)) {
            list($header, $body) = explode(self::DELIMITER . self::DELIMITER, $data, 2);
            $this->parseHeader($header);
            $this->processStringBody($body);
        } else {
            $this->parseHeader($data);
            $this->body = '';
        }
    }

    protected function parseHeader(string $header): void
    {
        $lines = explode(self::DELIMITER, $header);
        foreach ($lines as $line) {
            if (preg_match('/HTTP\/[^\s]+\s([0-9]{3})\s(.+)/ui', $line, $matches)) {
                // nothing more interesting for this code
                $this->code = intval($matches[1]);
                $this->reason = strval($matches[2]);
            }
        }
    }

    protected function processStringBody(string $body): void
    {
        // nothing more interesting for this case
        $this->body = $body;
    }
}

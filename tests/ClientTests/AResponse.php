<?php

namespace ClientTests;


use CommonTestClass;


abstract class AResponse extends CommonTestClass
{
    const DELIMITER = "\r\n";

    public function responseProvider(): array
    {
        return [
            [$this->getResponseSimple(), 900, 'abcdefghijkl'],
            [$this->getResponseEmpty(), 901, ''],
            [$this->getResponseHeaders(), 902, 'abcdefghijkl'],
        ];
    }

    protected function getResponseSimple()
    {
        return 'HTTP/0.1 900 KO' . self::DELIMITER . self::DELIMITER . 'abcdefghijkl';
    }

    protected function getResponseEmpty()
    {
        return 'HTTP/0.1 901 KO';
    }

    protected function getResponseHeaders()
    {
        return 'HTTP/0.1 902 KO' . self::DELIMITER
            . 'Server: PhpUnit/9.3.0' . self::DELIMITER
            . 'Content-Length: 12' . self::DELIMITER
            . 'Content-Type: text/plain' . self::DELIMITER
            . 'Connection: Closed' . self::DELIMITER
            . self::DELIMITER
            . 'abcdefghijkl'
            ;
    }

}

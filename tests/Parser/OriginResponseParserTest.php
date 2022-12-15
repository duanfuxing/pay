<?php

namespace duan617\Pay\Tests\Parser;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidResponseException;
use duan617\Pay\Parser\OriginResponseParser;
use duan617\Pay\Tests\TestCase;

class OriginResponseParserTest extends TestCase
{
    public function testResponseNull()
    {
        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::INVALID_RESPONSE_CODE);

        $parser = new OriginResponseParser();
        $parser->parse(null);
    }
}

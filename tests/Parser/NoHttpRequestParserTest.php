<?php

namespace duan617\Pay\Tests\Parser;

use GuzzleHttp\Psr7\Response;
use duan617\Pay\Parser\NoHttpRequestParser;
use duan617\Pay\Tests\TestCase;

class NoHttpRequestParserTest extends TestCase
{
    public function testNormal()
    {
        $response = new Response(200, [], '{"name": "duan617"}');

        $parser = new NoHttpRequestParser();
        $result = $parser->parse($response);

        self::assertSame($response, $result);
    }

    public function testNull()
    {
        $parser = new NoHttpRequestParser();
        $result = $parser->parse(null);

        self::assertNull($result);
    }
}

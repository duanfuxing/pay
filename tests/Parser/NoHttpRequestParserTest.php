<?php

namespace Yansongda\Pay\Tests\Parser;

use GuzzleHttp\Psr7\Response;
use Yansongda\Pay\Parser\NoHttpRequestParser;
use Yansongda\Pay\Tests\TestCase;

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

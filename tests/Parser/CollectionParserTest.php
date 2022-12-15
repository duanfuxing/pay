<?php

namespace duan617\Pay\Tests\Parser;

use GuzzleHttp\Psr7\Response;
use duan617\Pay\Parser\CollectionParser;
use duan617\Pay\Pay;
use duan617\Pay\Tests\TestCase;

class CollectionParserTest extends TestCase
{
    public function testNormal()
    {
        Pay::config([]);

        $response = new Response(200, [], '{"name": "duan617"}');

        $parser = new CollectionParser();
        $result = $parser->parse($response);

        self::assertEquals(['name' => 'duan617'], $result->all());
    }
}

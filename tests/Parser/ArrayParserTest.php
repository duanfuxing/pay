<?php

namespace duan617\Pay\Tests\Parser;

use GuzzleHttp\Psr7\Response;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidResponseException;
use duan617\Pay\Parser\ArrayParser;
use duan617\Pay\Tests\TestCase;

class ArrayParserTest extends TestCase
{
    public function testResponseNull()
    {
        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::RESPONSE_NONE);

        $parser = new ArrayParser();
        $parser->parse(null);
    }

    public function testWrongFormat()
    {
        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::UNPACK_RESPONSE_ERROR);

        $response = new Response(200, [], '{"name": "duan617"}a');

        $parser = new ArrayParser();
        $parser->parse($response);
    }

    public function testNormal()
    {
        $response = new Response(200, [], '{"name": "duan617"}');

        $parser = new ArrayParser();
        $result = $parser->parse($response);

        self::assertEquals(['name' => 'duan617'], $result);
    }

    public function testReadContents()
    {
        $response = new Response(200, [], '{"name": "duan617"}');

        $response->getBody()->read(2);

        $parser = new ArrayParser();
        $result = $parser->parse($response);

        self::assertEquals(['name' => 'duan617'], $result);
    }

    public function testQueryBody()
    {
        $response = new Response(200, [], 'name=duan617&age=29');

        $parser = new ArrayParser();
        $result = $parser->parse($response);

        self::assertEqualsCanonicalizing(['name' => 'duan617', 'age' => '29'], $result);
    }

    public function testJsonWith()
    {
        $url = 'https://duan617.cn?name=duan617&age=29';

        $response = new Response(200, [], json_encode(['h5_url' => $url]));

        $parser = new ArrayParser();
        $result = $parser->parse($response);

        self::assertEquals('https://duan617.cn?name=duan617&age=29', $result['h5_url']);
    }
}

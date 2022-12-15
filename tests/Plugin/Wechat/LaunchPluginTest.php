<?php

namespace duan617\Pay\Tests\Plugin\Wechat;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidResponseException;
use duan617\Pay\Parser\NoHttpRequestParser;
use duan617\Pay\Parser\OriginResponseParser;
use duan617\Pay\Plugin\Wechat\LaunchPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class LaunchPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\LaunchPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new LaunchPlugin();
    }

    public function testShouldNotDoRequest()
    {
        $rocket = new Rocket();
        $rocket->setDirection(NoHttpRequestParser::class);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($rocket, $result);
    }

    public function testOriginalResponseDestination()
    {
        $destination = new Response();

        $rocket = new Rocket();
        $rocket->setDirection(OriginResponseParser::class);
        $rocket->setDestination($destination);
        $rocket->setDestinationOrigin(new ServerRequest('POST', 'http://localhost'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($destination, $result->getDestination());
    }

    public function testOriginalResponseCodeErrorDestination()
    {
        $destination = new Response(500);

        $rocket = new Rocket();
        $rocket->setDirection(OriginResponseParser::class);
        $rocket->setDestination($destination);
        $rocket->setDestinationOrigin(new ServerRequest('POST', 'http://localhost'));

        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::INVALID_RESPONSE_CODE);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testArrayDestination()
    {
        $destination = [];

        $rocket = new Rocket();
        $rocket->setDirection(OriginResponseParser::class);
        $rocket->setDestination($destination);
        $rocket->setDestinationOrigin(new ServerRequest('POST', 'http://localhost'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals($destination, $result->getDestination());
    }

    public function testCollectionDestination()
    {
        $destination = new Collection();

        $rocket = new Rocket();
        $rocket->setDirection(OriginResponseParser::class);
        $rocket->setDestination($destination);
        $rocket->setDestinationOrigin(new ServerRequest('POST', 'http://localhost'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($destination, $result->getDestination());
    }
}

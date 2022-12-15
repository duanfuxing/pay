<?php

namespace duan617\Pay\Tests\Plugin\Unipay\OnlineGateway;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use duan617\Pay\Parser\ResponseParser;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Unipay\OnlineGateway\PagePayPlugin;
use duan617\Pay\Provider\Unipay;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class PagePayPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Unipay\OnlineGateway\PagePayPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PagePayPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Unipay::URL[Pay::MODE_NORMAL].'gateway/api/frontTransReq.do'), $radar->getUri());
        self::assertEquals(ResponseParser::class, $result->getDirection());
        self::assertEquals('000201', $payload['bizType']);
        self::assertEquals('01', $payload['txnType']);
        self::assertEquals('01', $payload['txnSubType']);
        self::assertEquals('07', $payload['channelType']);
    }
}

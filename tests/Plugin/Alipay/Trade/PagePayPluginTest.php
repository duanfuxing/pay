<?php

namespace duan617\Pay\Tests\Plugin\Alipay\Trade;

use duan617\Pay\Parser\ResponseParser;
use duan617\Pay\Plugin\Alipay\Trade\PagePayPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class PagePayPluginTest extends TestCase
{
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

        self::assertEquals(ResponseParser::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.page.pay', $result->getPayload()->toJson());
        self::assertStringContainsString('FAST_INSTANT_TRADE_PAY', $result->getPayload()->toJson());
    }
}

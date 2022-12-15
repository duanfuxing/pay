<?php

namespace duan617\Pay\Tests\Plugin\Alipay\Trade;

use duan617\Pay\Parser\ResponseParser;
use duan617\Pay\Plugin\Alipay\Trade\PageRefundPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class PageRefundPluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PageRefundPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(ResponseParser::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.page.refund', $result->getPayload()->toJson());
    }
}

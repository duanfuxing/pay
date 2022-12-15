<?php

namespace duan617\Pay\Tests\Plugin\Alipay\Fund;

use duan617\Pay\Parser\ResponseParser;
use duan617\Pay\Plugin\Alipay\Fund\TransPagePayPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class TransPagePayPluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new TransPagePayPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(ResponseParser::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.trans.page.pay', $result->getPayload()->toJson());
    }
}

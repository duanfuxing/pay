<?php

namespace duan617\Pay\Tests\Plugin\Alipay\Fund;

use duan617\Pay\Parser\ResponseParser;
use duan617\Pay\Plugin\Alipay\Fund\AuthOrderFreezePlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class AuthOrderFreezePluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AuthOrderFreezePlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseParser::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.auth.order.freeze', $result->getPayload()->toJson());
        self::assertStringContainsString('PRE_AUTH', $result->getPayload()->toJson());
    }
}

<?php

namespace duan617\Pay\Tests\Plugin\Alipay\Fund;

use duan617\Pay\Parser\ResponseParser;
use duan617\Pay\Plugin\Alipay\Fund\TransCommonQueryPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class TransCommonQueryPluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new TransCommonQueryPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $payloadString = $result->getPayload()->toJson();

        self::assertNotEquals(ResponseParser::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.trans.common.query', $payloadString);
        self::assertStringContainsString('TRANS_ACCOUNT_NO_PWD', $payloadString);
    }
}

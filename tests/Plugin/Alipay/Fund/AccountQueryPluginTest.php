<?php

namespace duan617\Pay\Tests\Plugin\Alipay\Fund;

use duan617\Pay\Parser\ResponseParser;
use duan617\Pay\Plugin\Alipay\Fund\AccountQueryPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class AccountQueryPluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AccountQueryPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseParser::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.account.query', $result->getPayload()->toJson());
        self::assertStringContainsString('TRANS_ACCOUNT_NO_PWD', $result->getPayload()->toJson());
    }
}

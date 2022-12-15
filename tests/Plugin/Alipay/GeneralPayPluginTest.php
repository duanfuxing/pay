<?php

namespace duan617\Pay\Tests\Plugin\Alipay;

use duan617\Pay\Rocket;
use duan617\Pay\Tests\Stubs\Plugin\AlipayGeneralPluginStub;
use duan617\Pay\Tests\TestCase;

class GeneralPayPluginTest extends TestCase
{
    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $plugin = new AlipayGeneralPluginStub();

        $result = $plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertStringContainsString('duan617', $result->getPayload()->toJson());
    }
}


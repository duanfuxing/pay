<?php

namespace duan617\Pay\Tests\Plugin\Alipay\Tools;

use duan617\Pay\Parser\ResponseParser;
use duan617\Pay\Plugin\Alipay\Tools\SystemOauthTokenPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class SystemOauthTokenPluginTest extends TestCase
{
    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams(['name' => 'duan617', 'age' => 28]);

        $plugin = new SystemOauthTokenPlugin();

        $result = $plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('duan617', $result->getPayload()->get('name'));
        self::assertEquals(28, $result->getPayload()->get('age'));
    }
}

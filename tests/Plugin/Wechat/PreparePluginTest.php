<?php

namespace duan617\Pay\Tests\Plugin\Wechat;

use duan617\Pay\Plugin\Wechat\PreparePlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class PreparePluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\PreparePlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PreparePlugin();
    }

    public function testNormal()
    {
        $params = [
            'name' => 'duan617',
            '_aaa' => 'aaa',
        ];

        $rocket = (new Rocket())->setParams($params);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $payload = $result->getPayload()->all();

        self::assertEquals('duan617', $payload['name']);
        self::assertArrayNotHasKey('aaa', $payload);
        self::assertArrayNotHasKey('_aaa', $payload);
    }
}

<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Pay\App;

use duan617\Pay\Plugin\Wechat\Pay\App\PrepayPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class PrepayPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\Pay\App\PrepayPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PrepayPlugin();
    }

    public function testWechatIdNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals('duan617', $payload->get('appid'));
        self::assertEquals('1600314069', $payload->get('mchid'));
    }

    public function testWechatIdNormalWithType()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_type' => 'mini'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals('duan617', $payload->get('appid'));
        self::assertEquals('1600314069', $payload->get('mchid'));
    }

    public function testWechatIdPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals('wx55955316af4ef16', $payload->get('sub_appid'));
        self::assertEquals('1600314070', $payload->get('sub_mchid'));
    }

    public function testWechatIdPartnerDirect()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['sub_appid' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals('123', $payload->get('sub_appid'));
        self::assertEquals('1600314070', $payload->get('sub_mchid'));
    }
}

<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Pay\Mini;

use duan617\Pay\Plugin\Wechat\Pay\Mini\InvokePrepayPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;
use function duan617\Pay\get_wechat_config;

class InvokePrepayPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\Pay\Mini\InvokePrepayPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new InvokePrepayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setParams([])->setDestination(new Collection(['prepay_id' => 'duan617 anthony']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();
        $config = get_wechat_config($rocket->getParams());

        self::assertArrayHasKey('appId', $contents->all());
        self::assertEquals($config['mini_app_id'], $contents->get('appId'));
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertArrayHasKey('package', $contents->all());
        self::assertArrayHasKey('signType', $contents->all());
        self::assertArrayHasKey('paySign', $contents->all());
    }

    public function testPartnerSpAppId()
    {
        $rocket = (new Rocket())->setParams(['_config' => 'service_provider']);
        $rocket->setPayload(new Collection(['out_trade_no'=>'121218']));
        $rocket->setDestination(new Collection(['prepay_id' => 'duan617 anthony']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();
        $config = get_wechat_config($rocket->getParams());

        self::assertArrayHasKey('appId', $contents->all());
        self::assertEquals($config['mini_app_id'], $contents->get('appId'));
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertArrayHasKey('package', $contents->all());
        self::assertArrayHasKey('signType', $contents->all());
        self::assertArrayHasKey('paySign', $contents->all());
    }

    public function testPartnerSubAppId()
    {
        $rocket = (new Rocket())->setParams(['_config' => 'service_provider']);
        $rocket->setPayload(new Collection(['out_trade_no'=>'121218','sub_appid' =>'wx55955316af4ef88']));
        $rocket->setDestination(new Collection(['prepay_id' => 'duan617 anthony']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('appId', $contents->all());
        self::assertEquals('wx55955316af4ef88', $contents->get('appId'));
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertArrayHasKey('package', $contents->all());
        self::assertArrayHasKey('signType', $contents->all());
        self::assertArrayHasKey('paySign', $contents->all());
    }
}

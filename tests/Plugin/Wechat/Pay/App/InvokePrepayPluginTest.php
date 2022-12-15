<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Pay\App;

use duan617\Pay\Plugin\Wechat\Pay\App\InvokePrepayPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class InvokePrepayPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\Pay\App\InvokePrepayPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new InvokePrepayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setDestination(new Collection(['prepay_id' => 'duan617']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('appid', $contents->all());
        self::assertArrayHasKey('partnerid', $contents->all());
        self::assertArrayHasKey('package', $contents->all());
        self::assertEquals('Sign=WXPay', $contents->get('package'));
        self::assertArrayHasKey('sign', $contents->all());
        self::assertArrayHasKey('timestamp', $contents->all());
        self::assertArrayHasKey('noncestr', $contents->all());
        self::assertEquals('duan617', $contents->get('appid'));
    }

    public function testPartner()
    {
        $rocket = (new Rocket())
            ->setParams(['_config' => 'service_provider4'])
            ->setPayload(new Collection(['sub_appid' => '123']))
            ->setDestination(new Collection(['prepay_id' => 'duan617']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('appid', $contents->all());
        self::assertEquals('123', $contents->get('appid'));
    }
}

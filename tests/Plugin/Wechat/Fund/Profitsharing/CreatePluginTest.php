<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Fund\Profitsharing;

use GuzzleHttp\Psr7\Uri;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\Fund\Profitsharing\CreatePlugin;
use duan617\Pay\Provider\Wechat;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class CreatePluginTest extends TestCase
{
    /**
     * @var CreatePlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CreatePlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/profitsharing/orders'), $radar->getUri());
        self::assertEquals('wx55955316af4ef13', $payload->get('appid'));
        self::assertArrayNotHasKey('sub_mchid', $payload->all());
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/profitsharing/orders'), $radar->getUri());
        self::assertEquals('wx55955316af4ef13', $payload->get('appid'));
        self::assertEquals('1600314070', $payload->get('sub_mchid'));
    }

    public function testPartnerDirectPayload()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['sub_mchid' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals('wx55955316af4ef13', $payload->get('appid'));
        self::assertEquals('123', $payload->get('sub_mchid'));
    }

    public function testEncryptName()
    {
        $params = [
            'receivers' => [
                [
                    'name' => 'duan617'
                ]
            ]
        ];

        $rocket = new Rocket();
        $rocket->setParams($params)->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $payload = $result->getPayload();

        self::assertNotEquals('duan617', $payload->get('receivers.0.name'));
        self::assertStringContainsString('==', $payload->get('receivers.0.name'));
    }
}

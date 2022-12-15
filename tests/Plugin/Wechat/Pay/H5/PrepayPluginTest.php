<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Pay\H5;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\Pay\H5\PrepayPlugin;
use duan617\Pay\Provider\Wechat;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class PrepayPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\Pay\H5\PrepayPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PrepayPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/pay/transactions/h5'), $radar->getUri());
        self::assertArrayNotHasKey('sp_appid', $payload->all());
        self::assertArrayNotHasKey('sp_mchid', $payload->all());
        self::assertArrayNotHasKey('sub_appid', $payload->all());
        self::assertArrayNotHasKey('sub_mchid', $payload->all());
        self::assertEquals('wx55955316af4ef13', $payload->get('appid'));
        self::assertEquals('1600314069', $payload->get('mchid'));
    }

    public function testNormalType()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_type' => 'mini'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/pay/transactions/h5'), $radar->getUri());
        self::assertArrayNotHasKey('sp_appid', $payload->all());
        self::assertArrayNotHasKey('sp_mchid', $payload->all());
        self::assertArrayNotHasKey('sub_appid', $payload->all());
        self::assertArrayNotHasKey('sub_mchid', $payload->all());
        self::assertEquals('wx55955316af4ef14', $payload->get('appid'));
        self::assertEquals('1600314069', $payload->get('mchid'));
    }

    public function testNormalTypeApp()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_type' => 'app'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/pay/transactions/h5'), $radar->getUri());
        self::assertArrayNotHasKey('sp_appid', $payload->all());
        self::assertArrayNotHasKey('sp_mchid', $payload->all());
        self::assertArrayNotHasKey('sub_appid', $payload->all());
        self::assertArrayNotHasKey('sub_mchid', $payload->all());
        self::assertEquals('duan617', $payload->get('appid'));
        self::assertEquals('1600314069', $payload->get('mchid'));
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/pay/partner/transactions/h5'), $radar->getUri());
        self::assertArrayNotHasKey('appid', $payload->all());
        self::assertArrayNotHasKey('mchid', $payload->all());
        self::assertEquals('wx55955316af4ef13', $payload->get('sp_appid'));
        self::assertEquals('1600314069', $payload->get('sp_mchid'));
        self::assertEquals('wx55955316af4ef15', $payload->get('sub_appid'));
        self::assertEquals('1600314070', $payload->get('sub_mchid'));
    }

    public function testPartnerType()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider', '_type' => 'mini'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/pay/partner/transactions/h5'), $radar->getUri());
        self::assertArrayNotHasKey('appid', $payload->all());
        self::assertArrayNotHasKey('mchid', $payload->all());
        self::assertEquals('wx55955316af4ef14', $payload->get('sp_appid'));
        self::assertEquals('1600314069', $payload->get('sp_mchid'));
        self::assertEquals('wx55955316af4ef17', $payload->get('sub_appid'));
        self::assertEquals('1600314070', $payload->get('sub_mchid'));
    }

    public function testPartnerDirectPayload()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['sub_appid' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals('123', $payload->get('sub_appid'));
        self::assertEquals('1600314070', $payload->get('sub_mchid'));
    }
}

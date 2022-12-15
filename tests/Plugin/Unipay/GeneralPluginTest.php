<?php

namespace duan617\Pay\Tests\Plugin\Unipay;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use duan617\Pay\Pay;
use duan617\Pay\Provider\Unipay;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\Stubs\Plugin\UnipayGeneralPluginStub;
use duan617\Pay\Tests\Stubs\Plugin\UnipayGeneralPluginStub1;
use duan617\Pay\Tests\TestCase;

class GeneralPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Tests\Stubs\Plugin\UnipayGeneralPluginStub
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new UnipayGeneralPluginStub();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Unipay::URL[Pay::MODE_NORMAL].'duan617/pay'), $radar->getUri());
    }

    public function testAbsoluteUrl()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = (new UnipayGeneralPluginStub1())->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri('https://duan617.cn/pay'), $radar->getUri());
    }
}

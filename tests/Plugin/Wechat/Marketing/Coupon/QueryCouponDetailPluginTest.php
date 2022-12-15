<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Marketing\Coupon;

use GuzzleHttp\Psr7\Uri;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\Marketing\Coupon\QueryCouponDetailPlugin;
use duan617\Pay\Provider\Wechat;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class QueryCouponDetailPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\Marketing\Coupon\QueryCouponDetailPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryCouponDetailPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection([
            'coupon_id' => '123456',
            'openid' => '7890',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals('GET', $radar->getMethod());
        self::assertNull($result->getPayload());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/marketing/favor/users/7890/coupons/123456?appid=wx55955316af4ef13'), $radar->getUri());
    }
}

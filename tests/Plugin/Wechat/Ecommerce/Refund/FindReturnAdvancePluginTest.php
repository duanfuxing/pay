<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Ecommerce\Refund;

use GuzzleHttp\Psr7\Uri;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\Ecommerce\Refund\FindReturnAdvancePlugin;
use duan617\Pay\Provider\Wechat;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class FindReturnAdvancePluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\Ecommerce\Refund\FindReturnAdvancePlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new FindReturnAdvancePlugin();
    }

    public function testNotInServiceMode()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection());

        $this->expectException(InvalidParamsException::class);
        $this->expectExceptionCode(Exception::NOT_IN_SERVICE_MODE);

        $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });
    }

    public function testMissingRefundId()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection());

        $this->expectException(InvalidParamsException::class);
        $this->expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['refund_id' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/ecommerce/refunds/123/return-advance?sub_mchid=1600314070'), $radar->getUri());
        self::assertEquals('GET', $radar->getMethod());
    }
}

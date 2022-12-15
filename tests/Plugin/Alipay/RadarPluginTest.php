<?php

namespace duan617\Pay\Tests\Plugin\Alipay;

use duan617\Pay\Plugin\Alipay\RadarPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class RadarPluginTest extends TestCase
{
    public function testPostNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['name' => 'duan617']));

        $plugin = new RadarPlugin();

        $result = $plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('name=duan617', $result->getRadar()->getBody()->getContents());
        self::assertEquals('POST', $result->getRadar()->getMethod());
    }

    public function testGetNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_method' => 'get'])->setPayload(new Collection(['name' => 'duan617']));

        $plugin = new RadarPlugin();

        $result = $plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('GET', $result->getRadar()->getMethod());
    }
}

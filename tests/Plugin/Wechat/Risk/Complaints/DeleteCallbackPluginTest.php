<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Risk\Complaints;

use GuzzleHttp\Psr7\Uri;
use duan617\Pay\Parser\OriginResponseParser;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\Risk\Complaints\DeleteCallbackPlugin;
use duan617\Pay\Provider\Wechat;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class DeleteCallbackPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\Risk\Complaints\DeleteCallbackPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new DeleteCallbackPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['complaint_id' => '123', 'foo' => 'bar']));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/merchant-service/complaint-notifications'), $radar->getUri());
        self::assertNull($rocket->getPayload());
        self::assertEquals('DELETE', $radar->getMethod());
        self::assertEquals(OriginResponseParser::class, $rocket->getDirection());
    }
}

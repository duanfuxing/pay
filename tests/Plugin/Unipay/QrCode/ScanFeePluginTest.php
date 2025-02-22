<?php

namespace duan617\Pay\Tests\Plugin\Unipay\QrCode;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Unipay\QrCode\ScanFeePlugin;
use duan617\Pay\Provider\Unipay;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;

class ScanFeePluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Unipay\QrCode\ScanFeePlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ScanFeePlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Unipay::URL[Pay::MODE_NORMAL].'gateway/api/backTransReq.do'), $radar->getUri());
        self::assertEquals('000601', $payload['bizType']);
        self::assertEquals('13', $payload['txnType']);
        self::assertEquals('08', $payload['txnSubType']);
        self::assertEquals('08', $payload['channelType']);
    }
}

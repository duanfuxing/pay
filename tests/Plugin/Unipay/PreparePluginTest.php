<?php

namespace duan617\Pay\Tests\Plugin\Unipay;

use duan617\Pay\Contract\ConfigInterface;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Unipay\PreparePlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use function duan617\Pay\get_unipay_config;

class PreparePluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Unipay\PreparePlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PreparePlugin();
    }

    public function testNormal()
    {
        $params = [
            'txnTime' => '20220903065448',
            'txnAmt' => 1,
            'orderId' => 'duan61720220903065448',
        ];
        $payload = array_merge($params, [
            'version' => '5.1.0',
            'encoding' => 'utf-8',
            'backUrl' => 'https://duan617.cn/unipay/notify',
            'accessType' => '0',
            'signature' => '',
            'signMethod' => '01',
            'merId' => '777290058167151',
            'frontUrl' => 'https://duan617.cn/unipay/return',
            'certId' => '69903319369',
            'currencyCode' => '156',
        ]);

        $rocket = (new Rocket())->setParams($params);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $config = get_unipay_config([]);

        self::assertEquals($payload, $result->getPayload()->all());
        self::assertArrayHasKey('cert', $config['certs']);
        self::assertArrayHasKey('pkey', $config['certs']);
        self::assertEquals('69903319369', $config['certs']['cert_id']);

        Pay::get(ConfigInterface::class)->set('unipay.default.mch_cert_path', null);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertTrue(true);
    }
}

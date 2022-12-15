<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Fund\Transfer;

use GuzzleHttp\Psr7\Uri;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\Fund\Transfer\CreatePlugin;
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

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/transfer/batches'), $radar->getUri());
        self::assertEquals('wx55955316af4ef13', $payload->get('appid'));
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/partner-transfer/batches'), $radar->getUri());
        self::assertEquals('1600314070', $payload->get('sub_mchid'));
        self::assertNull($payload->get('appid'));
    }

    public function testUsername()
    {
        // 不传证书
        $params = [
            'transfer_detail_list' => [
                [
                    'out_detail_no' => time().'-1',
                    'transfer_amount' => 1,
                    'transfer_remark' => 'test',
                    'openid' => 'MYE42l80oelYMDE34nYD456Xoy',
                    'user_name' => 'duan617'  // 明文传参即可，sdk 会自动加密
                ]
            ],
        ];

        $rocket = new Rocket();
        $rocket->setParams($params)->setPayload(new Collection());
        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $userName = $result->getPayload()->get('transfer_detail_list')[0]['user_name'];

        self::assertTrue(in_array($result->getParams()['_serial_no'], ['45F59D4DABF31918AFCEC556D5D2C6E376675D57', 'duan617']));
        self::assertNotEquals('duan617', $userName);
        self::assertStringContainsString('==', $userName);

        // 传证书
        $params = [
            '_serial_no' => 'duan617',
            'transfer_detail_list' => [
                [
                    'out_detail_no' => time().'-1',
                    'transfer_amount' => 1,
                    'transfer_remark' => 'test',
                    'openid' => 'MYE42l80oelYMDE34nYD456Xoy',
                    'user_name' => 'duan617'  // 明文传参即可，sdk 会自动加密
                ]
            ],
        ];

        $rocket = new Rocket();
        $rocket->setParams($params)->setPayload(new Collection());
        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $userName = $result->getPayload()->get('transfer_detail_list')[0]['user_name'];

        self::assertEquals('duan617', $result->getParams()['_serial_no']);
        self::assertStringContainsString('==', $userName);
    }

    public function testNormalOtherType()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_type' => 'mini'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/transfer/batches'), $radar->getUri());
        self::assertEquals('wx55955316af4ef14', $payload->get('appid'));
    }
}

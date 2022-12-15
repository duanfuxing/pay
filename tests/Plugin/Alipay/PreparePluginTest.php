<?php

namespace duan617\Pay\Tests\Plugin\Alipay;

use duan617\Pay\Contract\ConfigInterface;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidConfigException;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Alipay\PreparePlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Config;

class PreparePluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PreparePlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $payload = $result->getPayload();

        self::assertTrue($payload->has('app_cert_sn'));
        self::assertEquals('fb5e86cfb784de936dd3594e32381cf8', $payload->get('app_cert_sn'));
        self::assertEquals('687b59193f3f462dd5336e5abf83c5d8_02941eef3187dddf3d3b83462e1dfcf6', $payload->get('alipay_root_cert_sn'));
        self::assertEquals('duan617_token', $payload->get('app_auth_token'));
    }

    public function testGlobalBcscale()
    {
        bcscale(2);

        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('687b59193f3f462dd5336e5abf83c5d8_02941eef3187dddf3d3b83462e1dfcf6', $result->getPayload()->get('alipay_root_cert_sn'));
    }

    public function testCustomizedReturnUrl()
    {
        $rocket = new Rocket();
        $rocket->setParams([
            '_return_url' => 'https://duan617.cn',
        ]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('', $result->getPayload()->get('notify_url'));
        self::assertEquals('https://duan617.cn', $result->getPayload()->get('return_url'));
    }

    public function testCustomizedNotifyUrl()
    {
        $rocket = new Rocket();
        $rocket->setParams([
            '_notify_url' => 'https://duan617.cn',
        ]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('', $result->getPayload()->get('return_url'));
        self::assertEquals('https://duan617.cn', $result->getPayload()->get('notify_url'));
    }

    public function testCustomizedReturnNotifyUrl()
    {
        $rocket = new Rocket();
        $rocket->setParams([
            '_return_url' => 'https://duan617.cn',
            '_notify_url' => 'https://duan617.cn',
        ]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('https://duan617.cn', $result->getPayload()->get('return_url'));
        self::assertEquals('https://duan617.cn', $result->getPayload()->get('notify_url'));
    }

    public function testCustomizedAppAuthToken()
    {
        $rocket = new Rocket();
        $rocket->setParams([
            '_app_auth_token' => 'duan617.cn',
        ]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('duan617.cn', $result->getPayload()->get('app_auth_token'));
    }

    public function testMissingAppPublicCertPath()
    {
        $rocket = new Rocket();

        Pay::set(ConfigInterface::class, new Config());

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::ALIPAY_CONFIG_ERROR);
        self::expectExceptionMessage('Missing Alipay Config -- [app_public_cert_path]');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testWrongAppPublicCertPath()
    {
        $rocket = new Rocket();
        $config = Pay::get(ConfigInterface::class);
        $config->set('alipay.default.app_public_cert_path', __DIR__.'/../../Cert/foo');

        Pay::set(ConfigInterface::class, $config);

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::ALIPAY_CONFIG_ERROR);
        self::expectExceptionMessage('Parse `app_public_cert_path` Error');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testMissingAlipayRootPath()
    {
        $rocket = new Rocket();
        $config = Pay::get(ConfigInterface::class);

        $config->set('alipay.default.alipay_root_cert_path', null);

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::ALIPAY_CONFIG_ERROR);
        self::expectExceptionMessage('Missing Alipay Config -- [alipay_root_cert_path]');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testWrongAlipayRootPath()
    {
        $rocket = new Rocket();
        $config = Pay::get(ConfigInterface::class);

        $config->set('alipay.default.alipay_root_cert_path', __DIR__.'/../../Cert/foo');

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::ALIPAY_CONFIG_ERROR);
        self::expectExceptionMessage('Invalid alipay_root_cert');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testAppCertSnCached()
    {
        $result = $this->plugin->assembly(new Rocket(), function ($rocket) { return $rocket; });
        $payload = $result->getPayload();

        self::assertEquals('fb5e86cfb784de936dd3594e32381cf8', $payload->get('app_cert_sn'));

        $config = Pay::get(ConfigInterface::class);
        $config->set('alipay.default.app_public_cert_path', null);

        $result = $this->plugin->assembly(new Rocket(), function ($rocket) { return $rocket; });
        $payload = $result->getPayload();

        self::assertEquals('fb5e86cfb784de936dd3594e32381cf8', $payload->get('app_cert_sn'));
    }

    public function testAlipayRootCertSnCached()
    {
        $result = $this->plugin->assembly(new Rocket(), function ($rocket) { return $rocket; });
        $payload = $result->getPayload();

        self::assertEquals('687b59193f3f462dd5336e5abf83c5d8_02941eef3187dddf3d3b83462e1dfcf6', $payload->get('alipay_root_cert_sn'));

        $config = Pay::get(ConfigInterface::class);
        $config->set('alipay.default.alipay_root_cert_path', null);

        $result = $this->plugin->assembly(new Rocket(), function ($rocket) { return $rocket; });
        $payload = $result->getPayload();

        self::assertEquals('687b59193f3f462dd5336e5abf83c5d8_02941eef3187dddf3d3b83462e1dfcf6', $payload->get('alipay_root_cert_sn'));
    }
}

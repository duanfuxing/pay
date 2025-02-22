<?php

namespace duan617\Pay\Tests\Traits;

use duan617\Pay\Contract\ConfigInterface;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidConfigException;
use duan617\Pay\Pay;
use duan617\Pay\Tests\Stubs\Traits\GetUnipayCertsStub;
use duan617\Pay\Tests\TestCase;
use function duan617\Pay\get_unipay_config;

class GetUnipayCertsTest extends TestCase
{
    /**
     * @var GetUnipayCertsStub
     */
    protected $trait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trait = new GetUnipayCertsStub();
    }

    public function testNormal()
    {
        $certId = $this->trait->getCertId('default', get_unipay_config([]));

        $config = get_unipay_config([]);

        self::assertEquals('69903319369', $certId);
        self::assertArrayHasKey('cert', $config['certs']);
        self::assertArrayHasKey('pkey', $config['certs']);
    }

    public function testMissingCert()
    {
        $config = Pay::get(ConfigInterface::class);
        $config->set('unipay.default.mch_cert_path', null);

        $this->expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::UNIPAY_CONFIG_ERROR);
        $this->expectExceptionMessage('Missing Unipay Config -- [mch_cert_path] or [mch_cert_password]');

        $this->trait->getCertId('default', get_unipay_config([]));
    }

    public function testMissingCertPassword()
    {
        $config = Pay::get(ConfigInterface::class);
        $config->set('unipay.default.mch_cert_password', null);

        $this->expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::UNIPAY_CONFIG_ERROR);
        $this->expectExceptionMessage('Missing Unipay Config -- [mch_cert_path] or [mch_cert_password]');

        $this->trait->getCertId('default', get_unipay_config([]));
    }

    public function testWrongCert()
    {
        $config = Pay::get(ConfigInterface::class);

        $config->set('unipay.default.mch_cert_path', __DIR__.'/../Cert/foo');

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::UNIPAY_CONFIG_ERROR);
        self::expectExceptionMessage('Read `mch_cert_path` Error');

        $this->trait->getCertId('default', get_unipay_config([]));
    }

    public function testNormalCached()
    {
        $certId = $this->trait->getCertId('default', get_unipay_config([]));

        $config = get_unipay_config([]);

        self::assertEquals('69903319369', $certId);
        self::assertArrayHasKey('cert', $config['certs']);
        self::assertArrayHasKey('pkey', $config['certs']);

        Pay::get(ConfigInterface::class)->set('unipay.default.mch_cert_path', null);

        $this->trait->getCertId('default', get_unipay_config([]));

        self::assertTrue(true);
    }
}

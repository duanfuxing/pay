<?php

declare(strict_types=1);

namespace duan617\Pay\Tests\Plugin\Unipay\Shortcut;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Unipay\QrCode\ScanFeePlugin;
use duan617\Pay\Plugin\Unipay\QrCode\ScanNormalPlugin;
use duan617\Pay\Plugin\Unipay\QrCode\ScanPreAuthPlugin;
use duan617\Pay\Plugin\Unipay\QrCode\ScanPreOrderPlugin;
use duan617\Pay\Plugin\Unipay\Shortcut\ScanShortcut;
use duan617\Pay\Tests\TestCase;

class ScanShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ScanShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            ScanNormalPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testPreAuth()
    {
        self::assertEquals([
            ScanPreAuthPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'pre_auth']));
    }

    public function testPreOrder()
    {
        self::assertEquals([
            ScanPreOrderPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'pre_order']));
    }

    public function testFee()
    {
        self::assertEquals([
            ScanFeePlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'fee']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_TYPE_ERROR);
        self::expectExceptionMessage('Scan type [fooPlugins] not supported');

        $this->plugin->getPlugins(['_type' => 'foo']);
    }
}

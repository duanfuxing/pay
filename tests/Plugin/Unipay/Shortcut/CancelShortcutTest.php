<?php

declare(strict_types=1);

namespace duan617\Pay\Tests\Plugin\Unipay\Shortcut;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Unipay\OnlineGateway\CancelPlugin;
use duan617\Pay\Plugin\Unipay\Shortcut\CancelShortcut;
use duan617\Pay\Tests\TestCase;

class CancelShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CancelShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            CancelPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testQrCode()
    {
        self::assertEquals([
            \duan617\Pay\Plugin\Unipay\QrCode\CancelPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'qr_code']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_TYPE_ERROR);
        self::expectExceptionMessage('Cancel type [fooPlugins] not supported');

        $this->plugin->getPlugins(['_type' => 'foo']);
    }
}

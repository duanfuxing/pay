<?php

declare(strict_types=1);

namespace duan617\Pay\Tests\Plugin\Unipay\Shortcut;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Unipay\QrCode\PosNormalPlugin;
use duan617\Pay\Plugin\Unipay\QrCode\PosPreAuthPlugin;
use duan617\Pay\Plugin\Unipay\Shortcut\PosShortcut;
use duan617\Pay\Tests\TestCase;

class PosShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PosShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            PosNormalPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testPreAuth()
    {
        self::assertEquals([
            PosPreAuthPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'pre_auth']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_TYPE_ERROR);
        self::expectExceptionMessage('Pos type [fooPlugins] not supported');

        $this->plugin->getPlugins(['_type' => 'foo']);
    }
}

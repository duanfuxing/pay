<?php

declare(strict_types=1);

namespace duan617\Pay\Tests\Plugin\Wechat\Shortcut;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Alipay\Fund\TransCommonQueryPlugin;
use duan617\Pay\Plugin\Wechat\Pay\Common\ClosePlugin;
use duan617\Pay\Plugin\Wechat\Shortcut\CloseShortcut;
use duan617\Pay\Tests\TestCase;

class CloseShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CloseShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            ClosePlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testCombine()
    {
        self::assertEquals([
            \duan617\Pay\Plugin\Wechat\Pay\Combine\ClosePlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'combine']));
    }

    public function testCombineParams()
    {
        self::assertEquals([
            \duan617\Pay\Plugin\Wechat\Pay\Combine\ClosePlugin::class,
        ], $this->plugin->getPlugins(['combine_out_trade_no' => '123abc']));

        self::assertEquals([
            \duan617\Pay\Plugin\Wechat\Pay\Combine\ClosePlugin::class,
        ], $this->plugin->getPlugins(['sub_orders' => '123abc']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_TYPE_ERROR);
        self::expectExceptionMessage('Query type [fooPlugins] not supported');

        $this->plugin->getPlugins(['_type' => 'foo']);
    }
}

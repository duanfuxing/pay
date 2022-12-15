<?php

declare(strict_types=1);

namespace duan617\Pay\Tests\Plugin\Alipay\Shortcut;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Alipay\Fund\TransCommonQueryPlugin;
use duan617\Pay\Plugin\Alipay\Shortcut\QueryShortcut;
use duan617\Pay\Plugin\Alipay\Trade\FastRefundQueryPlugin;
use duan617\Pay\Plugin\Alipay\Trade\QueryPlugin;
use duan617\Pay\Tests\TestCase;

class QueryShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            QueryPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testRefund()
    {
        self::assertEquals([
            FastRefundQueryPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'refund']));
    }

    public function testTransfer()
    {
        self::assertEquals([
            TransCommonQueryPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'transfer']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_TYPE_ERROR);
        self::expectExceptionMessage('Query type [fooPlugins] not supported');

        $this->plugin->getPlugins(['_type' => 'foo']);
    }
}

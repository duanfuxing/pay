<?php

declare(strict_types=1);

namespace duan617\Pay\Tests\Plugin\Unipay\Shortcut;

use duan617\Pay\Plugin\Unipay\HtmlResponsePlugin;
use duan617\Pay\Plugin\Unipay\OnlineGateway\WapPayPlugin;
use duan617\Pay\Plugin\Unipay\Shortcut\WapShortcut;
use duan617\Pay\Tests\TestCase;

class WapShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WapShortcut();
    }

    public function test()
    {
        self::assertEquals([
            WapPayPlugin::class,
            HtmlResponsePlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Tests\Plugin\Unipay\Shortcut;

use duan617\Pay\Plugin\Unipay\HtmlResponsePlugin;
use duan617\Pay\Plugin\Unipay\OnlineGateway\PagePayPlugin;
use duan617\Pay\Plugin\Unipay\Shortcut\WebShortcut;
use duan617\Pay\Tests\TestCase;

class WebShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WebShortcut();
    }

    public function test()
    {
        self::assertEquals([
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Shortcut;

use duan617\Pay\Contract\ShortcutInterface;
use duan617\Pay\Plugin\Alipay\HtmlResponsePlugin;
use duan617\Pay\Plugin\Alipay\Trade\PagePayPlugin;

class WebShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}

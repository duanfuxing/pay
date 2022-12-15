<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Unipay\Shortcut;

use duan617\Pay\Contract\ShortcutInterface;
use duan617\Pay\Plugin\Unipay\HtmlResponsePlugin;
use duan617\Pay\Plugin\Unipay\OnlineGateway\WapPayPlugin;

class WapShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            WapPayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}

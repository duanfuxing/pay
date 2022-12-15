<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Shortcut;

use duan617\Pay\Contract\ShortcutInterface;
use duan617\Pay\Plugin\Wechat\Pay\Jsapi\InvokePrepayPlugin;
use duan617\Pay\Plugin\Wechat\Pay\Jsapi\PrepayPlugin;

class MpShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PrepayPlugin::class,
            InvokePrepayPlugin::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Shortcut;

use duan617\Pay\Contract\ShortcutInterface;
use duan617\Pay\Plugin\Wechat\Fund\Transfer\CreatePlugin;

class TransferShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            CreatePlugin::class,
        ];
    }
}

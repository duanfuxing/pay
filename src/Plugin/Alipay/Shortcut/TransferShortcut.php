<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Shortcut;

use duan617\Pay\Contract\ShortcutInterface;
use duan617\Pay\Plugin\Alipay\Fund\TransUniTransferPlugin;

class TransferShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            TransUniTransferPlugin::class,
        ];
    }
}

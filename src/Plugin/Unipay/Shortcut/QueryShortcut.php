<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Unipay\Shortcut;

use duan617\Pay\Contract\ShortcutInterface;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Unipay\OnlineGateway\QueryPlugin;
use Yansongda\Supports\Str;

class QueryShortcut implements ShortcutInterface
{
    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = Str::camel($params['_type'] ?? 'default').'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_TYPE_ERROR, "Query type [$typeMethod] not supported");
    }

    public function defaultPlugins(): array
    {
        return [
            QueryPlugin::class,
        ];
    }

    protected function qrCodePlugins(): array
    {
        return [
            \duan617\Pay\Plugin\Unipay\QrCode\QueryPlugin::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Shortcut;

use duan617\Pay\Contract\ShortcutInterface;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Wechat\Pay\Common\FindRefundPlugin;
use duan617\Pay\Plugin\Wechat\Pay\Common\QueryPlugin;
use Yansongda\Supports\Str;

class QueryShortcut implements ShortcutInterface
{
    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        if (isset($params['combine_out_trade_no'])) {
            return $this->combinePlugins();
        }

        $typeMethod = Str::camel($params['_type'] ?? 'default').'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_TYPE_ERROR, "Query type [$typeMethod] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            QueryPlugin::class,
        ];
    }

    protected function refundPlugins(): array
    {
        return [
            FindRefundPlugin::class,
        ];
    }

    protected function combinePlugins(): array
    {
        return [
            \duan617\Pay\Plugin\Wechat\Pay\Combine\QueryPlugin::class,
        ];
    }
}

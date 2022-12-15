<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat;

use Closure;
use duan617\Pay\Contract\PluginInterface;
use duan617\Pay\Logger;
use duan617\Pay\Rocket;
use Yansongda\Supports\Str;

class PreparePlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[wechat][PreparePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload($this->getPayload($rocket->getParams()));

        Logger::info('[wechat][PreparePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function getPayload(array $params): array
    {
        return array_filter($params, fn ($v, $k) => !Str::startsWith(strval($k), '_'), ARRAY_FILTER_USE_BOTH);
    }
}

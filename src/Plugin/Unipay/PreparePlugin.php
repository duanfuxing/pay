<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Unipay;

use Closure;
use duan617\Pay\Contract\PluginInterface;

use function duan617\Pay\get_tenant;
use function duan617\Pay\get_unipay_config;

use duan617\Pay\Logger;
use duan617\Pay\Rocket;
use duan617\Pay\Traits\GetUnipayCerts;
use Yansongda\Supports\Str;

class PreparePlugin implements PluginInterface
{
    use GetUnipayCerts;

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[unipay][PreparePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload($this->getPayload($rocket->getParams()));

        Logger::info('[unipay][PreparePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     */
    protected function getPayload(array $params): array
    {
        $tenant = get_tenant($params);
        $config = get_unipay_config($params);

        $init = [
            'version' => '5.1.0',
            'encoding' => 'utf-8',
            'backUrl' => $config['notify_url'] ?? '',
            'currencyCode' => '156',
            'accessType' => '0',
            'signature' => '',
            'signMethod' => '01',
            'merId' => $config['mch_id'] ?? '',
            'frontUrl' => $config['return_url'] ?? '',
            'certId' => $this->getCertId($tenant, $config),
        ];

        return array_merge(
            $init,
            array_filter($params, fn ($v, $k) => !Str::startsWith(strval($k), '_'), ARRAY_FILTER_USE_BOTH),
        );
    }
}

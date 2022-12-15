<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Unipay;

use Closure;
use duan617\Pay\Contract\PluginInterface;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidResponseException;
use duan617\Pay\Logger;
use duan617\Pay\Parser\NoHttpRequestParser;
use duan617\Pay\Rocket;

use function duan617\Pay\verify_unipay_sign;

use Yansongda\Supports\Collection;
use Yansongda\Supports\Str;

class CallbackPlugin implements PluginInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\InvalidResponseException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[unipay][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->formatPayload($rocket);

        $params = $rocket->getParams();
        $signature = $params['signature'] ?? false;

        if (!$signature) {
            throw new InvalidResponseException(Exception::INVALID_RESPONSE_SIGN, '', $params);
        }

        verify_unipay_sign($params, $rocket->getPayload()->sortKeys()->toString(), $signature);

        $rocket->setDirection(NoHttpRequestParser::class)
            ->setDestination($rocket->getPayload());

        Logger::info('[unipay][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function formatPayload(Rocket $rocket): void
    {
        $payload = (new Collection($rocket->getParams()))
            ->filter(fn ($v, $k) => 'signature' != $k && !Str::startsWith($k, '_'));

        $rocket->setPayload($payload);
    }
}

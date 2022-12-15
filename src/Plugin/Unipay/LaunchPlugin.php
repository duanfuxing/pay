<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Unipay;

use Closure;
use duan617\Pay\Contract\PluginInterface;
use duan617\Pay\Logger;
use duan617\Pay\Rocket;

use function duan617\Pay\should_do_http_request;
use function duan617\Pay\verify_unipay_sign;

use Yansongda\Supports\Collection;

class LaunchPlugin implements PluginInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     * @throws \duan617\Pay\Exception\InvalidResponseException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::info('[unipay][LaunchPlugin] 插件开始装载', ['rocket' => $rocket]);

        if (should_do_http_request($rocket->getDirection())) {
            $response = Collection::wrap($rocket->getDestination());
            $signature = $response->get('signature');
            $response->forget('signature');

            verify_unipay_sign(
                $rocket->getParams(), $response->sortKeys()->toString(), $signature
            );

            $rocket->setDestination($response);
        }

        Logger::info('[unipay][LaunchPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}

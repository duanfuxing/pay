<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat;

use Closure;
use Psr\Http\Message\ResponseInterface;
use duan617\Pay\Contract\PluginInterface;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidResponseException;
use duan617\Pay\Logger;
use duan617\Pay\Rocket;

use function duan617\Pay\should_do_http_request;
use function duan617\Pay\verify_wechat_sign;

class LaunchPlugin implements PluginInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     * @throws \duan617\Pay\Exception\InvalidResponseException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::info('[wechat][LaunchPlugin] 插件开始装载', ['rocket' => $rocket]);

        if (should_do_http_request($rocket->getDirection())) {
            verify_wechat_sign($rocket->getDestinationOrigin(), $rocket->getParams());

            $rocket->setDestination($this->validateResponse($rocket));
        }

        Logger::info('[wechat][LaunchPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }

    /**
     * @return array|\Psr\Http\Message\MessageInterface|\Yansongda\Supports\Collection|null
     *
     * @throws \duan617\Pay\Exception\InvalidResponseException
     */
    protected function validateResponse(Rocket $rocket)
    {
        $response = $rocket->getDestination();

        if ($response instanceof ResponseInterface &&
            ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300)) {
            throw new InvalidResponseException(Exception::INVALID_RESPONSE_CODE);
        }

        return $response;
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat;

use Closure;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ServerRequestInterface;
use duan617\Pay\Contract\PluginInterface;

use function duan617\Pay\decrypt_wechat_resource;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Logger;
use duan617\Pay\Parser\NoHttpRequestParser;
use duan617\Pay\Rocket;

use function duan617\Pay\verify_wechat_sign;

use Yansongda\Supports\Collection;

class CallbackPlugin implements PluginInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\InvalidResponseException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[wechat][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->formatRequestAndParams($rocket);

        /* @phpstan-ignore-next-line */
        verify_wechat_sign($rocket->getDestinationOrigin(), $rocket->getParams());

        $body = json_decode((string) $rocket->getDestination()->getBody(), true);

        $rocket->setDirection(NoHttpRequestParser::class)->setPayload(new Collection($body));

        $body['resource'] = decrypt_wechat_resource($body['resource'] ?? [], $rocket->getParams());

        $rocket->setDestination(new Collection($body));

        Logger::info('[wechat][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function formatRequestAndParams(Rocket $rocket): void
    {
        $request = $rocket->getParams()['request'] ?? null;

        if (!($request instanceof ServerRequestInterface)) {
            throw new InvalidParamsException(Exception::REQUEST_NULL_ERROR);
        }

        $contents = (string) $request->getBody();

        $rocket->setDestination($request->withBody(Utils::streamFor($contents)))
            ->setDestinationOrigin($request->withBody(Utils::streamFor($contents)))
            ->setParams($rocket->getParams()['params'] ?? []);
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat;

use Closure;
use Psr\Http\Message\RequestInterface;
use duan617\Pay\Contract\PluginInterface;

use function duan617\Pay\get_wechat_base_uri;
use function duan617\Pay\get_wechat_config;

use duan617\Pay\Logger;
use duan617\Pay\Pay;
use duan617\Pay\Request;
use duan617\Pay\Rocket;

abstract class GeneralPlugin implements PluginInterface
{
    /**
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[wechat][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->getRequest($rocket));
        $this->doSomething($rocket);

        Logger::info('[wechat][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getRequest(Rocket $rocket): RequestInterface
    {
        return new Request(
            $this->getMethod(),
            $this->getUrl($rocket),
            $this->getHeaders(),
        );
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getUrl(Rocket $rocket): string
    {
        $params = $rocket->getParams();

        $url = Pay::MODE_SERVICE === (get_wechat_config($params)['mode'] ?? null) ? $this->getPartnerUri($rocket) : $this->getUri($rocket);

        return 0 === strpos($url, 'http') ? $url : (get_wechat_base_uri($params).$url);
    }

    protected function getHeaders(): array
    {
        return [
            'Accept' => 'application/json, text/plain, application/x-gzip',
            'User-Agent' => 'duan617/pay-v3',
            'Content-Type' => 'application/json; charset=utf-8',
        ];
    }

    abstract protected function doSomething(Rocket $rocket): void;

    abstract protected function getUri(Rocket $rocket): string;

    protected function getPartnerUri(Rocket $rocket): string
    {
        return $this->getUri($rocket);
    }
}

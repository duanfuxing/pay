<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Unipay;

use Closure;
use Psr\Http\Message\RequestInterface;
use duan617\Pay\Contract\PluginInterface;

use function duan617\Pay\get_unipay_config;

use duan617\Pay\Logger;
use duan617\Pay\Pay;
use duan617\Pay\Provider\Unipay;
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
        Logger::info('[unipay][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->getRequest($rocket));
        $this->doSomething($rocket);

        Logger::info('[unipay][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

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
        $url = $this->getUri($rocket);

        if (0 === strpos($url, 'http')) {
            return $url;
        }

        $config = get_unipay_config($rocket->getParams());

        return Unipay::URL[$config['mode'] ?? Pay::MODE_NORMAL].$url;
    }

    protected function getHeaders(): array
    {
        return [
            'User-Agent' => 'duan617/pay-v3',
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];
    }

    abstract protected function doSomething(Rocket $rocket): void;

    abstract protected function getUri(Rocket $rocket): string;
}

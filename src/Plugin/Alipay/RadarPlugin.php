<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay;

use Closure;
use Psr\Http\Message\RequestInterface;
use duan617\Pay\Contract\PluginInterface;

use function duan617\Pay\get_alipay_config;

use duan617\Pay\Logger;
use duan617\Pay\Pay;
use duan617\Pay\Provider\Alipay;
use duan617\Pay\Request;
use duan617\Pay\Rocket;

/**
 * @deprecated use RadarSignPlugin instead
 */
class RadarPlugin implements PluginInterface
{
    /**
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[alipay][RadarPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->getRequest($rocket));

        Logger::info('[alipay][RadarPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getRequest(Rocket $rocket): RequestInterface
    {
        return new Request(
            $this->getMethod($rocket),
            $this->getUrl($rocket),
            $this->getHeaders(),
            $this->getBody($rocket),
        );
    }

    protected function getMethod(Rocket $rocket): string
    {
        return strtoupper($rocket->getParams()['_method'] ?? 'POST');
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getUrl(Rocket $rocket): string
    {
        $config = get_alipay_config($rocket->getParams());

        return Alipay::URL[$config['mode'] ?? Pay::MODE_NORMAL];
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    protected function getBody(Rocket $rocket): string
    {
        return $rocket->getPayload()->query();
    }
}

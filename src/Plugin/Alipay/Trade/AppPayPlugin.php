<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Trade;

use Closure;
use duan617\Pay\Contract\PluginInterface;
use duan617\Pay\Logger;
use duan617\Pay\Parser\ResponseParser;
use duan617\Pay\Rocket;
use duan617\Pay\Traits\SupportServiceProviderTrait;

/**
 * @see https://opendocs.alipay.com/open/02e7gq?scene=common
 */
class AppPayPlugin implements PluginInterface
{
    use SupportServiceProviderTrait;

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[alipay][AppPayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->loadAlipayServiceProvider($rocket);

        $rocket->setDirection(ResponseParser::class)
            ->mergePayload([
            'method' => 'alipay.trade.app.pay',
            'biz_content' => array_merge(
                ['product_code' => 'QUICK_MSECURITY_PAY'],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[alipay][AppPayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}

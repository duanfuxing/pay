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
 * @see https://opendocs.alipay.com/open/02ivbs?scene=common
 */
class WapPayPlugin implements PluginInterface
{
    use SupportServiceProviderTrait;

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[alipay][WapPayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->loadAlipayServiceProvider($rocket);

        $rocket->setDirection(ResponseParser::class)
            ->mergePayload([
            'method' => 'alipay.trade.wap.pay',
            'biz_content' => array_merge(
                [
                    'product_code' => 'QUICK_WAP_PAY',
                ],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[alipay][WapPayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}

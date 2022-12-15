<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Data;

use Closure;
use duan617\Pay\Contract\PluginInterface;
use duan617\Pay\Logger;
use duan617\Pay\Rocket;

/**
 * @see https://opendocs.alipay.com/open/029p6g
 */
class BillEreceiptApplyPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[alipay][BillEreceiptApplyPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.data.bill.ereceipt.apply',
            'biz_content' => array_merge(
                [
                    'type' => 'FUND_DETAIL',
                ],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[alipay][BillEreceiptApplyPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}

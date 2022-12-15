<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Ebpp;

use Closure;
use duan617\Pay\Contract\PluginInterface;
use duan617\Pay\Logger;
use duan617\Pay\Rocket;

/**
 * @see https://opendocs.alipay.com/open/02hd35
 */
class PdeductPayPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[alipay][PdeductPayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.ebpp.pdeduct.pay',
            'biz_content' => array_merge(
                [
                    'agent_channel' => 'PUBLICFORM',
                ],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[alipay][PdeductPayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}

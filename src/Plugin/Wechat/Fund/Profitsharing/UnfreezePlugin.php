<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Profitsharing;

use function duan617\Pay\get_wechat_config;

use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_5.shtml
 */
class UnfreezePlugin extends GeneralPlugin
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $payload = $rocket->getPayload();
        $config = get_wechat_config($rocket->getParams());

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null) && !$payload->has('sub_mchid')) {
            $rocket->mergePayload([
                'sub_mchid' => $config['sub_mch_id'] ?? '',
            ]);
        }
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/profitsharing/orders/unfreeze';
    }
}

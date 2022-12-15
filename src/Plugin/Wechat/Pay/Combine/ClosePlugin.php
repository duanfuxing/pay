<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Pay\Combine;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;

use function duan617\Pay\get_wechat_config;

use duan617\Pay\Rocket;
use Yansongda\Supports\Collection;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter5_1_12.shtml
 */
class ClosePlugin extends \duan617\Pay\Plugin\Wechat\Pay\Common\ClosePlugin
{
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('combine_out_trade_no')) &&
            is_null($payload->get('out_trade_no'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/combine-transactions/out-trade-no/'.
            $payload->get('combine_out_trade_no', $payload->get('out_trade_no')).
            '/close';
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());

        $rocket->setPayload(new Collection([
            'combine_appid' => $config['combine_appid'] ?? '',
            'sub_orders' => $rocket->getParams()['sub_orders'] ?? [],
        ]));
    }
}

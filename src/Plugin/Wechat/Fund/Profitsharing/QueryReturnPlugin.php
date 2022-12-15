<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Profitsharing;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;

use function duan617\Pay\get_wechat_config;

use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_4.shtml
 */
class QueryReturnPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();
        $config = get_wechat_config($rocket->getParams());

        if (is_null($payload->get('out_return_no')) ||
            is_null($payload->get('out_order_no'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $url = 'v3/profitsharing/return-orders/'.
            $payload->get('out_return_no').
            '?out_order_no='.$payload->get('out_order_no');

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $url .= '&sub_mchid='.$payload->get('sub_mchid', $config['sub_mch_id'] ?? '');
        }

        return $url;
    }
}

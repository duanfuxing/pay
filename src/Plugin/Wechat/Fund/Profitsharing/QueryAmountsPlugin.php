<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Profitsharing;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_6.shtml
 */
class QueryAmountsPlugin extends GeneralPlugin
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
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('transaction_id'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/profitsharing/transactions/'.
            $payload->get('transaction_id').
            '/amounts';
    }
}

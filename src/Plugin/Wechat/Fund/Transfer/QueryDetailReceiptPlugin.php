<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Transfer;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_10.shtml
 */
class QueryDetailReceiptPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('out_detail_no')) || is_null($payload->get('accept_type'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $rocket->setPayload(null);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/transfer-detail/electronic-receipts?'.$rocket->getPayload()->query();
    }
}

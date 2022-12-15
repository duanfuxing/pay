<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Transfer;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_6.shtml
 */
class QueryOutBatchDetailNoPlugin extends GeneralPlugin
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

        if (is_null($payload->get('out_batch_no')) || is_null($payload->get('out_detail_no'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/transfer/batches/out-batch-no/'.
            $payload->get('out_batch_no').
            '/details/out-detail-no/'.
            $payload->get('out_detail_no');
    }

    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('out_batch_no')) || is_null($payload->get('out_detail_no'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/partner-transfer/batches/out-batch-no/'.
            $payload->get('out_batch_no').
            '/details/out-detail-no/'.
            $payload->get('out_detail_no');
    }
}

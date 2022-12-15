<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Transfer;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_5.shtml
 */
class QueryOutBatchNoPlugin extends GeneralPlugin
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

        if (is_null($payload->get('out_batch_no')) || is_null($payload->get('need_query_detail'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $outBatchNo = $payload->get('out_batch_no');

        $payload->forget('out_batch_no');

        return 'v3/transfer/batches/out-batch-no/'.$outBatchNo.
            '?'.$payload->query();
    }

    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('out_batch_no')) || is_null($payload->get('need_query_detail'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $outBatchNo = $payload->get('out_batch_no');

        $payload->forget('out_batch_no');

        return 'v3/partner-transfer/batches/out-batch-no/'.$outBatchNo.
            '?'.$payload->query();
    }
}

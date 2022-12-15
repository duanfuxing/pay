<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Transfer;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_2.shtml
 */
class QueryBatchIdPlugin extends GeneralPlugin
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

        if (is_null($payload->get('batch_id')) || is_null($payload->get('need_query_detail'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $batchId = $payload->get('batch_id');

        $payload->forget('batch_id');

        return 'v3/transfer/batches/batch-id/'.$batchId.
            '?'.$payload->query();
    }

    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('batch_id')) || is_null($payload->get('need_query_detail'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $batchId = $payload->get('batch_id');

        $payload->forget('batch_id');

        return 'v3/partner-transfer/batches/batch-id/'.$batchId.
            '?'.$payload->query();
    }
}

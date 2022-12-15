<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Pay\Common;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;

use function duan617\Pay\get_wechat_config;

use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

class QueryPlugin extends GeneralPlugin
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $config = get_wechat_config($rocket->getParams());
        $payload = $rocket->getPayload();

        if (!is_null($payload->get('transaction_id'))) {
            return 'v3/pay/transactions/id/'.
                $payload->get('transaction_id').
                '?mchid='.($config['mch_id'] ?? '');
        }

        if (!is_null($payload->get('out_trade_no'))) {
            return 'v3/pay/transactions/out-trade-no/'.
                $payload->get('out_trade_no').
                '?mchid='.($config['mch_id'] ?? '');
        }

        throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $config = get_wechat_config($rocket->getParams());
        $payload = $rocket->getPayload();

        if (!is_null($payload->get('transaction_id'))) {
            return 'v3/pay/partner/transactions/id/'.
                $payload->get('transaction_id').
                '?sp_mchid='.($config['mch_id'] ?? '').
                '&sub_mchid='.$payload->get('sub_mchid', $config['sub_mch_id'] ?? null);
        }

        if (!is_null($payload->get('out_trade_no'))) {
            return 'v3/pay/partner/transactions/out-trade-no/'.
                $payload->get('out_trade_no').
                '?sp_mchid='.($config['mch_id'] ?? '').
                '&sub_mchid='.$payload->get('sub_mchid', $config['sub_mch_id'] ?? null);
        }

        throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }
}

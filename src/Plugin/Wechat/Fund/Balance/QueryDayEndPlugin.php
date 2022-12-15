<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Balance;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

class QueryDayEndPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
    }

    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('account_type')) || is_null($payload->get('date'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/merchant/fund/dayendbalance/'.
            $payload->get('account_type').
            '?date='.$payload->get('date');
    }
}

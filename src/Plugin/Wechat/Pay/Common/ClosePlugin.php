<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Pay\Common;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;

use function duan617\Pay\get_wechat_config;

use duan617\Pay\Parser\OriginResponseParser;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;
use Yansongda\Supports\Collection;

class ClosePlugin extends GeneralPlugin
{
    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('out_trade_no'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/pay/transactions/out-trade-no/'.
            $payload->get('out_trade_no').
            '/close';
    }

    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('out_trade_no'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/pay/partner/transactions/out-trade-no/'.
            $payload->get('out_trade_no').
            '/close';
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setDirection(OriginResponseParser::class);

        $config = get_wechat_config($rocket->getParams());

        $body = [
            'mchid' => $config['mch_id'] ?? '',
        ];

        if (Pay::MODE_SERVICE == ($config['mode'] ?? null)) {
            $body = [
                'sp_mchid' => $config['mch_id'] ?? '',
                'sub_mchid' => $rocket->getPayload()->get('sub_mchid', $config['sub_mch_id'] ?? ''),
            ];
        }

        $rocket->setPayload(new Collection($body));
    }
}

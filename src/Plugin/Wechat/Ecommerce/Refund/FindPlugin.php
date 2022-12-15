<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Ecommerce\Refund;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;

use function duan617\Pay\get_wechat_config;

use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter7_6_2.shtml
 */
class FindPlugin extends GeneralPlugin
{
    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        throw new InvalidParamsException(Exception::NOT_IN_SERVICE_MODE);
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();
        $config = get_wechat_config($rocket->getParams());
        $subMchId = $payload->get('sub_mchid', $config['sub_mch_id'] ?? '');

        if (!is_null($payload->get('refund_id'))) {
            return 'v3/ecommerce/refunds/id/'.$payload->get('refund_id').'?sub_mchid='.$subMchId;
        }

        if (!is_null($payload->get('out_refund_no'))) {
            return 'v3/ecommerce/refunds/out-refund-no/'.$payload->get('out_refund_no').'?sub_mchid='.$subMchId;
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

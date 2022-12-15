<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Ecommerce\Refund;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;

use function duan617\Pay\get_wechat_config;

use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter7_6_1.shtml
 */
class ApplyPlugin extends GeneralPlugin
{
    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        throw new InvalidParamsException(Exception::NOT_IN_SERVICE_MODE);
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'v3/ecommerce/refunds/apply';
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());
        $payload = $rocket->getPayload();

        $key = ($rocket->getParams()['_type'] ?? 'mp').'_app_id';
        if ('app_app_id' === $key) {
            $key = 'app_id';
        }

        $wechatId = [
            'sub_mchid' => $payload->get('sub_mchid', $config['sub_mch_id'] ?? ''),
            'sp_appid' => $payload->get('sp_appid', $config[$key] ?? ''),
        ];

        if (!$payload->has('notify_url')) {
            $wechatId['notify_url'] = $config['notify_url'] ?? null;
        }

        $rocket->mergePayload($wechatId);
    }
}

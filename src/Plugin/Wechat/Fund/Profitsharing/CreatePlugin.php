<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Profitsharing;

use function duan617\Pay\encrypt_wechat_contents;
use function duan617\Pay\get_wechat_config;

use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Traits\HasWechatEncryption;
use Yansongda\Supports\Collection;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_1.shtml
 */
class CreatePlugin extends GeneralPlugin
{
    use HasWechatEncryption;

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\InvalidResponseException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $payload = $rocket->getPayload();
        $params = $this->loadSerialNo($rocket->getParams());

        $extra = $this->getWechatExtra($params, $payload);
        $extra['receivers'] = $this->getReceivers($params);

        $rocket->setParams($params);
        $rocket->mergePayload($extra);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/profitsharing/orders';
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getWechatExtra(array $params, Collection $payload): array
    {
        $config = get_wechat_config($params);

        $extra = [
            'appid' => $config['mp_app_id'] ?? null,
        ];

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $extra['sub_mchid'] = $payload->get('sub_mchid', $config['sub_mch_id'] ?? '');
        }

        return $extra;
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getReceivers(array $params): array
    {
        $publicKey = $this->getPublicKey($params, $params['_serial_no'] ?? '');
        $receivers = $params['receivers'] ?? [];

        foreach ($receivers as $key => $receiver) {
            if (!empty($receiver['name'])) {
                $receivers[$key]['name'] = encrypt_wechat_contents($receiver['name'], $publicKey);
            }
        }

        return $receivers;
    }
}

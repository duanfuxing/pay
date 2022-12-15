<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Fund\Transfer;

use function duan617\Pay\encrypt_wechat_contents;
use function duan617\Pay\get_wechat_config;

use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Traits\HasWechatEncryption;
use Yansongda\Supports\Collection;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_1.shtml
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
        $params = $rocket->getParams();
        $extra = $this->getWechatId($params, $rocket->getPayload());

        if (!empty($params['transfer_detail_list'][0]['user_name'] ?? '')) {
            $params = $this->loadSerialNo($params);

            $rocket->setParams($params);

            $extra['transfer_detail_list'] = $this->getEncryptUserName($params);
        }

        $rocket->mergePayload($extra);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/transfer/batches';
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'v3/partner-transfer/batches';
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getWechatId(array $params, Collection $payload): array
    {
        $config = get_wechat_config($params);
        $key = ($params['_type'] ?? 'mp').'_app_id';

        if ('app_app_id' === $key) {
            $key = 'app_id';
        }

        $appId = [
            'appid' => $payload->get('appid', $config[$key] ?? ''),
        ];

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $appId = [
                'sub_mchid' => $payload->get('sub_mchid', $config['sub_mch_id'] ?? ''),
            ];
        }

        return $appId;
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getEncryptUserName(array $params): array
    {
        $lists = $params['transfer_detail_list'] ?? [];
        $publicKey = $this->getPublicKey($params, $params['_serial_no'] ?? '');

        foreach ($lists as $key => $list) {
            $lists[$key]['user_name'] = encrypt_wechat_contents($list['user_name'], $publicKey);
        }

        return $lists;
    }
}

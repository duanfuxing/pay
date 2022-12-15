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
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_8.shtml
 */
class AddReceiverPlugin extends GeneralPlugin
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
        $config = get_wechat_config($rocket->getParams());
        $extra = $this->getWechatId($config, $rocket->getPayload());

        if (!empty($params['name'] ?? '')) {
            $params = $this->loadSerialNo($params);

            $name = $this->getEncryptUserName($params);
            $params['name'] = $name;
            $extra['name'] = $name;
            $rocket->setParams($params);
        }

        $rocket->mergePayload($extra);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/profitsharing/receivers/add';
    }

    protected function getWechatId(array $config, Collection $payload): array
    {
        $wechatId = [
            'appid' => $config['mp_app_id'] ?? null,
        ];

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $wechatId['sub_mchid'] = $payload->get('sub_mchid', $config['sub_mch_id'] ?? '');
        }

        return $wechatId;
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getEncryptUserName(array $params): string
    {
        $name = $params['name'] ?? '';
        $publicKey = $this->getPublicKey($params, $params['_serial_no'] ?? '');

        return encrypt_wechat_contents($name, $publicKey);
    }
}

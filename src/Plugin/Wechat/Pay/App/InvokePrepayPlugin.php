<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Pay\App;

use function duan617\Pay\get_wechat_config;
use function duan617\Pay\get_wechat_sign;

use duan617\Pay\Rocket;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Config;
use Yansongda\Supports\Str;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_2_4.shtml
 */
class InvokePrepayPlugin extends \duan617\Pay\Plugin\Wechat\Pay\Common\InvokePrepayPlugin
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \Exception
     */
    protected function getInvokeConfig(Rocket $rocket, string $prepayId): Config
    {
        $config = new Config([
            'appid' => $this->getAppId($rocket),
            'partnerid' => get_wechat_config($rocket->getParams())['mch_id'] ?? null,
            'prepayid' => $prepayId,
            'package' => 'Sign=WXPay',
            'noncestr' => Str::random(32),
            'timestamp' => time().'',
        ]);

        $config->set('sign', $this->getSign($config, $rocket->getParams()));

        return $config;
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getSign(Collection $invokeConfig, array $params): string
    {
        $contents = $invokeConfig->get('appid', '')."\n".
            $invokeConfig->get('timestamp', '')."\n".
            $invokeConfig->get('noncestr', '')."\n".
            $invokeConfig->get('prepayid', '')."\n";

        return get_wechat_sign($params, $contents);
    }

    protected function getConfigKey(): string
    {
        return 'app_id';
    }
}

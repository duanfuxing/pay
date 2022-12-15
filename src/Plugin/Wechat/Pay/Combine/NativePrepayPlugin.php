<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Pay\Combine;

use duan617\Pay\Plugin\Wechat\Pay\Common\CombinePrepayPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter5_1_5.shtml
 */
class NativePrepayPlugin extends CombinePrepayPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/combine-transactions/native';
    }
}

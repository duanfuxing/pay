<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Tools;

use duan617\Pay\Plugin\Alipay\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://opendocs.alipay.com/open/02ailc
 */
class SystemOauthTokenPlugin extends GeneralPlugin
{
    protected function doSomethingBefore(Rocket $rocket): void
    {
        $rocket->mergePayload($rocket->getParams());
    }

    protected function getMethod(): string
    {
        return 'alipay.system.oauth.token';
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Marketing\Coupon;

use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter9_1_4.shtml
 */
class QueryStocksPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/marketing/favor/stocks?'.$rocket->getPayload()->query();
    }
}

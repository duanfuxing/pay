<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Risk\Complaints;

use duan617\Pay\Parser\OriginResponseParser;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter10_2_5.shtml
 */
class DeleteCallbackPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'DELETE';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setDirection(OriginResponseParser::class);

        $rocket->setPayload(null);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/merchant-service/complaint-notifications';
    }
}

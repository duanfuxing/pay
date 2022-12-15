<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Pay\Common;

use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

class GetFlowBillPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/bill/fundflowbill?'.http_build_query($rocket->getParams());
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

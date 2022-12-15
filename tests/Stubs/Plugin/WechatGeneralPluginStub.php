<?php

namespace duan617\Pay\Tests\Stubs\Plugin;

use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

class WechatGeneralPluginStub extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'duan617/pay';
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'duan617/pay/partner';
    }
}

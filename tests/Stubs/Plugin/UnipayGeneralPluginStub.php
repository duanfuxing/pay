<?php

namespace duan617\Pay\Tests\Stubs\Plugin;

use duan617\Pay\Plugin\Unipay\GeneralPlugin;
use duan617\Pay\Rocket;

class UnipayGeneralPluginStub extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'duan617/pay';
    }
}

class UnipayGeneralPluginStub1 extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'https://duan617.cn/pay';
    }
}

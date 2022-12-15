<?php

namespace duan617\Pay\Tests\Stubs\Plugin;

use duan617\Pay\Plugin\Alipay\GeneralPlugin;

class AlipayGeneralPluginStub extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'duan617';
    }
}

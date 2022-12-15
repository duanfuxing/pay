<?php

namespace duan617\Pay\Tests\Stubs\Traits;

use duan617\Pay\Rocket;
use duan617\Pay\Traits\SupportServiceProviderTrait;

class SupportServiceProviderPluginStub
{
    use SupportServiceProviderTrait;

    public function assembly(Rocket $rocket)
    {
        $this->loadAlipayServiceProvider($rocket);
    }
}

<?php

namespace duan617\Pay\Tests\Traits;

use duan617\Pay\Pay;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\Stubs\Traits\SupportServiceProviderPluginStub;
use duan617\Pay\Tests\TestCase;

class SupportServiceProviderTraitTest extends TestCase
{
    public function testNormal()
    {
        Pay::config([
           '_force' => true,
           'alipay' => [
               'default' => [
                   'mode' => Pay::MODE_SERVICE,
                   'service_provider_id' => 'duan617'
               ]
           ]
        ]);

        $rocket = new Rocket();
        (new SupportServiceProviderPluginStub())->assembly($rocket);

        $result = json_encode($rocket->getParams());

        self::assertStringContainsString('extend_params', $result);
        self::assertStringContainsString('sys_service_provider_id', $result);
        self::assertStringContainsString('duan617', $result);
    }
}

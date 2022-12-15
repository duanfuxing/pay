<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Unipay\OnlineGateway;

use duan617\Pay\Plugin\Unipay\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=757&apiservId=448&version=V2.2&bussType=0
 */
class QueryPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'gateway/api/queryTrans.do';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([
            'bizType' => '000000',
            'txnType' => '00',
            'txnSubType' => '00',
        ]);
    }
}

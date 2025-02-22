<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Unipay\QrCode;

use duan617\Pay\Plugin\Unipay\GeneralPlugin;
use duan617\Pay\Rocket;

/**
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=800&apiservId=468&version=V2.2&bussType=0
 */
class CancelPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'gateway/api/backTransReq.do';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([
            'bizType' => '000000',
            'txnType' => '31',
            'txnSubType' => '00',
            'channelType' => '08',
        ]);
    }
}

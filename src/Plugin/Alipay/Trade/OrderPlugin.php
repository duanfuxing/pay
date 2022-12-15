<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Trade;

use duan617\Pay\Plugin\Alipay\GeneralPlugin;

class OrderPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.order.pay';
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Fund;

use duan617\Pay\Plugin\Alipay\GeneralPlugin;

/**
 * 老版本转账.
 *
 * @deprecated
 * @see https://opendocs.alipay.com/support/01rfzo
 */
class TransOrderQueryPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.fund.trans.order.query';
    }
}

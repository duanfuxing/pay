<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Trade;

use duan617\Pay\Plugin\Alipay\GeneralPlugin;

/**
 * @see https://opendocs.alipay.com/open/02o6e7
 */
class ClosePlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.close';
    }
}

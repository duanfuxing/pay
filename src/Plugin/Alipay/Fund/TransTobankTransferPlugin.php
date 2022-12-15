<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Alipay\Fund;

use duan617\Pay\Plugin\Alipay\GeneralPlugin;

class TransTobankTransferPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.fund.trans.tobank.transfer';
    }
}

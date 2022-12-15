<?php

declare(strict_types=1);

namespace duan617\Pay\Service;

use duan617\Pay\Contract\ParserInterface;
use duan617\Pay\Contract\ServiceProviderInterface;
use duan617\Pay\Parser\CollectionParser;
use duan617\Pay\Pay;
use duan617\Pay\Provider\Alipay;

class AlipayServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public function register($data = null): void
    {
        $service = new Alipay();

        Pay::set(ParserInterface::class, CollectionParser::class);
        Pay::set(Alipay::class, $service);
        Pay::set('alipay', $service);
    }
}

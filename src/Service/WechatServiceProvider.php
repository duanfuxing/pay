<?php

declare(strict_types=1);

namespace duan617\Pay\Service;

use duan617\Pay\Contract\ParserInterface;
use duan617\Pay\Contract\ServiceProviderInterface;
use duan617\Pay\Parser\CollectionParser;
use duan617\Pay\Pay;
use duan617\Pay\Provider\Wechat;

class WechatServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public function register($data = null): void
    {
        $service = new Wechat();

        Pay::set(ParserInterface::class, CollectionParser::class);
        Pay::set(Wechat::class, $service);
        Pay::set('wechat', $service);
    }
}

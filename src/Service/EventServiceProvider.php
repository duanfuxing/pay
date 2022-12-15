<?php

declare(strict_types=1);

namespace duan617\Pay\Service;

use Symfony\Component\EventDispatcher\EventDispatcher;
use duan617\Pay\Contract\EventDispatcherInterface;
use duan617\Pay\Contract\ServiceProviderInterface;
use duan617\Pay\Pay;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public function register($data = null): void
    {
        if (class_exists(EventDispatcher::class)) {
            Pay::set(EventDispatcherInterface::class, new EventDispatcher());
        }
    }
}

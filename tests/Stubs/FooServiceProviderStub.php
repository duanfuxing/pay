<?php

declare(strict_types=1);

namespace duan617\Pay\Tests\Stubs;

use duan617\Pay\Contract\ServiceProviderInterface;
use duan617\Pay\Pay;

class FooServiceProviderStub implements ServiceProviderInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public function register($data = null): void
    {
        Pay::set('foo', 'bar');
    }
}

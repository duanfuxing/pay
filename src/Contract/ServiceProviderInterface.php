<?php

declare(strict_types=1);

namespace duan617\Pay\Contract;

interface ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public function register($data = null): void;
}

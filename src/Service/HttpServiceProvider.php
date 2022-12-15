<?php

declare(strict_types=1);

namespace duan617\Pay\Service;

use GuzzleHttp\Client;
use duan617\Pay\Contract\ConfigInterface;
use duan617\Pay\Contract\HttpClientInterface;
use duan617\Pay\Contract\ServiceProviderInterface;
use duan617\Pay\Pay;

class HttpServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function register($data = null): void
    {
        /* @var \Yansongda\Supports\Config $config */
        $config = Pay::get(ConfigInterface::class);

        if (class_exists(Client::class)) {
            $service = new Client($config->get('http', []));

            Pay::set(HttpClientInterface::class, $service);
        }
    }
}

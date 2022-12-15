<?php

declare(strict_types=1);

namespace duan617\Pay\Service;

use duan617\Pay\Contract\ConfigInterface;
use duan617\Pay\Contract\ServiceProviderInterface;
use duan617\Pay\Pay;
use Yansongda\Supports\Config;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @var array
     */
    private $config = [
        'logger' => [
            'enable' => false,
            'file' => null,
            'identify' => 'duan617.pay',
            'level' => 'debug',
            'type' => 'daily',
            'max_files' => 30,
        ],
        'http' => [
            'timeout' => 5.0,
            'connect_timeout' => 3.0,
            'headers' => [
                'User-Agent' => 'duan617/pay-v3',
            ],
        ],
        'mode' => Pay::MODE_NORMAL,
    ];

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public function register($data = null): void
    {
        $config = new Config(array_replace_recursive($this->config, $data ?? []));

        Pay::set(ConfigInterface::class, $config);
    }
}

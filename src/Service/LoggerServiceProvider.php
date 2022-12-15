<?php

declare(strict_types=1);

namespace duan617\Pay\Service;

use duan617\Pay\Contract\ConfigInterface;
use duan617\Pay\Contract\LoggerInterface;
use duan617\Pay\Contract\ServiceProviderInterface;
use duan617\Pay\Pay;
use Yansongda\Supports\Logger;

class LoggerServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function register($data = null): void
    {
        /* @var ConfigInterface $config */
        $config = Pay::get(ConfigInterface::class);

        if (class_exists(\Monolog\Logger::class) && true === $config->get('logger.enable', false)) {
            $logger = new Logger(array_merge(
                ['identify' => 'duan617.pay'], $config->get('logger', [])
            ));

            Pay::set(LoggerInterface::class, $logger);
        }
    }
}

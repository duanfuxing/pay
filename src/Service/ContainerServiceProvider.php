<?php

declare(strict_types=1);

namespace duan617\Pay\Service;

use Closure;
use DI\ContainerBuilder;
use Hyperf\Utils\ApplicationContext as HyperfApplication;
use Illuminate\Container\Container as LaravelContainer;
use Psr\Container\ContainerInterface;
use Throwable;
use duan617\Pay\Contract\ServiceProviderInterface;
use duan617\Pay\Exception\ContainerException;
use duan617\Pay\Exception\ContainerNotFoundException;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Pay;

/**
 * @codeCoverageIgnore
 */
class ContainerServiceProvider implements ServiceProviderInterface
{
    private $detectApplication = [
        'laravel' => LaravelContainer::class,
        'hyperf' => HyperfApplication::class,
    ];

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public function register($data = null): void
    {
        if ($data instanceof ContainerInterface || $data instanceof Closure) {
            Pay::setContainer($data);

            return;
        }

        if (Pay::hasContainer()) {
            return;
        }

        foreach ($this->detectApplication as $framework => $application) {
            $method = $framework.'Application';

            if (class_exists($application) && method_exists($this, $method) && $this->{$method}()) {
                return;
            }
        }

        $this->defaultApplication();
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ContainerNotFoundException
     */
    protected function laravelApplication(): bool
    {
        Pay::setContainer(static fn () => LaravelContainer::getInstance());

        Pay::set(\duan617\Pay\Contract\ContainerInterface::class, LaravelContainer::getInstance());

        if (!Pay::has(ContainerInterface::class)) {
            Pay::set(ContainerInterface::class, LaravelContainer::getInstance());
        }

        return true;
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ContainerNotFoundException
     */
    protected function hyperfApplication(): bool
    {
        if (!HyperfApplication::hasContainer()) {
            return false;
        }

        Pay::setContainer(static fn () => HyperfApplication::getContainer());

        Pay::set(\duan617\Pay\Contract\ContainerInterface::class, HyperfApplication::getContainer());

        if (!Pay::has(ContainerInterface::class)) {
            Pay::set(ContainerInterface::class, HyperfApplication::getContainer());
        }

        return true;
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     */
    protected function defaultApplication(): void
    {
        if (!class_exists(ContainerBuilder::class)) {
            throw new ContainerNotFoundException('Init failed! Maybe you should install `php-di/php-di` first', Exception::CONTAINER_NOT_FOUND);
        }

        $builder = new ContainerBuilder();

        try {
            $container = $builder->build();
            $container->set(ContainerInterface::class, $container);
            $container->set(\duan617\Pay\Contract\ContainerInterface::class, $container);

            Pay::setContainer($container);
        } catch (Throwable $e) {
            throw new ContainerException($e->getMessage());
        }
    }
}

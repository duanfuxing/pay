<?php

declare(strict_types=1);

namespace duan617\Pay;

use Closure;
use Illuminate\Container\Container as LaravelContainer;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;
use duan617\Pay\Contract\ServiceProviderInterface;
use duan617\Pay\Exception\ContainerException;
use duan617\Pay\Exception\ContainerNotFoundException;
use duan617\Pay\Exception\ServiceNotFoundException;
use duan617\Pay\Provider\Alipay;
use duan617\Pay\Provider\Unipay;
use duan617\Pay\Provider\Wechat;
use duan617\Pay\Service\AlipayServiceProvider;
use duan617\Pay\Service\ConfigServiceProvider;
use duan617\Pay\Service\ContainerServiceProvider;
use duan617\Pay\Service\EventServiceProvider;
use duan617\Pay\Service\HttpServiceProvider;
use duan617\Pay\Service\LoggerServiceProvider;
use duan617\Pay\Service\UnipayServiceProvider;
use duan617\Pay\Service\WechatServiceProvider;

/**
 * @method static Alipay alipay(array $config = [], $container = null)
 * @method static Wechat wechat(array $config = [], $container = null)
 * @method static Unipay unipay(array $config = [], $container = null)
 */
class Pay
{
    /**
     * 正常模式.
     */
    public const MODE_NORMAL = 0;

    /**
     * 沙箱模式.
     */
    public const MODE_SANDBOX = 1;

    /**
     * 服务商模式.
     */
    public const MODE_SERVICE = 2;

    /**
     * @var string[]
     */
    protected array $service = [
        AlipayServiceProvider::class,
        WechatServiceProvider::class,
        UnipayServiceProvider::class,
    ];

    /**
     * @var string[]
     */
    private array $coreService = [
        ContainerServiceProvider::class,
        ConfigServiceProvider::class,
        LoggerServiceProvider::class,
        EventServiceProvider::class,
        HttpServiceProvider::class,
    ];

    /**
     * @var \Closure|\Psr\Container\ContainerInterface|null
     */
    private static $container = null;

    /**
     * @param \Closure|\Psr\Container\ContainerInterface|null $container
     *
     * @throws \duan617\Pay\Exception\ContainerException
     */
    private function __construct(array $config, $container = null)
    {
        $this->registerServices($config, $container);
    }

    /**
     * @return mixed
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public static function __callStatic(string $service, array $config)
    {
        if (!empty($config)) {
            self::config(...$config);
        }

        return self::get($service);
    }

    /**
     * @param \Closure|\Psr\Container\ContainerInterface|null $container
     *
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public static function config(array $config = [], $container = null): bool
    {
        if (self::hasContainer() && !($config['_force'] ?? false)) {
            return false;
        }

        new self($config, $container);

        return true;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $value
     *
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public static function set(string $name, $value): void
    {
        try {
            $container = Pay::getContainer();

            if ($container instanceof LaravelContainer) {
                $container->singleton($name, $value instanceof Closure ? $value : static fn () => $value);

                return;
            }

            if (method_exists($container, 'set')) {
                $container->set(...func_get_args());

                return;
            }
        } catch (ContainerNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException($e->getMessage());
        }

        throw new ContainerException('Current container does NOT support `set` method');
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     *
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public static function make(string $service, array $parameters = [])
    {
        try {
            $container = Pay::getContainer();

            if (method_exists($container, 'make')) {
                return $container->make(...func_get_args());
            }
        } catch (ContainerNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException($e->getMessage());
        }

        $parameters = array_values($parameters);

        return new $service(...$parameters);
    }

    /**
     * @return mixed
     *
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public static function get(string $service)
    {
        try {
            return Pay::getContainer()->get($service);
        } catch (NotFoundExceptionInterface $e) {
            throw new ServiceNotFoundException($e->getMessage());
        } catch (ContainerNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException($e->getMessage());
        }
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerNotFoundException
     */
    public static function has(string $service): bool
    {
        return Pay::getContainer()->has($service);
    }

    /**
     * @param \Closure|\Psr\Container\ContainerInterface|null $container
     */
    public static function setContainer($container): void
    {
        self::$container = $container;
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerNotFoundException
     */
    public static function getContainer(): ContainerInterface
    {
        if (self::$container instanceof ContainerInterface) {
            return self::$container;
        }

        if (self::$container instanceof Closure) {
            return (self::$container)();
        }

        throw new ContainerNotFoundException('`getContainer()` failed! Maybe you should `setContainer()` first', Exception\Exception::CONTAINER_NOT_FOUND);
    }

    public static function hasContainer(): bool
    {
        return self::$container instanceof ContainerInterface || self::$container instanceof Closure;
    }

    public static function clear(): void
    {
        self::$container = null;
    }

    /**
     * @param mixed $data
     *
     * @throws \duan617\Pay\Exception\ContainerException
     */
    public static function registerService(string $service, $data): void
    {
        $var = new $service();

        if ($var instanceof ServiceProviderInterface) {
            $var->register($data);
        }
    }

    /**
     * @param \Closure|\Psr\Container\ContainerInterface|null $container
     *
     * @throws \duan617\Pay\Exception\ContainerException
     */
    private function registerServices(array $config, $container = null): void
    {
        foreach (array_merge($this->coreService, $this->service) as $service) {
            self::registerService($service, ContainerServiceProvider::class == $service ? $container : $config);
        }
    }
}

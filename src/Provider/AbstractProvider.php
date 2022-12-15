<?php

declare(strict_types=1);

namespace duan617\Pay\Provider;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Client\ClientInterface;
use Throwable;
use duan617\Pay\Contract\HttpClientInterface;
use duan617\Pay\Contract\PluginInterface;
use duan617\Pay\Contract\ProviderInterface;
use duan617\Pay\Contract\ShortcutInterface;
use duan617\Pay\Event;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidConfigException;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Exception\InvalidResponseException;
use duan617\Pay\Logger;
use duan617\Pay\Parser\ArrayParser;
use duan617\Pay\Pay;
use duan617\Pay\Rocket;

use function duan617\Pay\should_do_http_request;

use Yansongda\Supports\Collection;
use Yansongda\Supports\Pipeline;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @return \Psr\Http\Message\MessageInterface|\Yansongda\Supports\Collection|array|null
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function call(string $plugin, array $params = [])
    {
        if (!class_exists($plugin) || !in_array(ShortcutInterface::class, class_implements($plugin))) {
            throw new InvalidParamsException(Exception::SHORTCUT_NOT_FOUND, "[$plugin] is not incompatible");
        }

        /* @var ShortcutInterface $money */
        $money = Pay::get($plugin);

        return $this->pay(
            $this->mergeCommonPlugins($money->getPlugins($params)), $params
        );
    }

    /**
     * @return \Psr\Http\Message\MessageInterface|\Yansongda\Supports\Collection|array|null
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    public function pay(array $plugins, array $params)
    {
        Logger::info('[AbstractProvider] 即将进行 pay 操作', func_get_args());

        Event::dispatch(new Event\PayStarted($plugins, $params, null));

        $this->verifyPlugin($plugins);

        /* @var Pipeline $pipeline */
        $pipeline = Pay::make(Pipeline::class);

        /* @var Rocket $rocket */
        $rocket = $pipeline
            ->send((new Rocket())->setParams($params)->setPayload(new Collection()))
            ->through($plugins)
            ->via('assembly')
            ->then(fn ($rocket) => $this->ignite($rocket));

        Event::dispatch(new Event\PayFinish($rocket));

        $destination = $rocket->getDestination();

        if (ArrayParser::class === $rocket->getDirection() && $destination instanceof Collection) {
            return $destination->toArray();
        }

        return $destination;
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\InvalidResponseException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     */
    public function ignite(Rocket $rocket): Rocket
    {
        if (!should_do_http_request($rocket->getDirection())) {
            return $rocket;
        }

        /* @var HttpClientInterface $http */
        $http = Pay::get(HttpClientInterface::class);

        if (!($http instanceof ClientInterface)) {
            throw new InvalidConfigException(Exception::HTTP_CLIENT_CONFIG_ERROR);
        }

        Logger::info('[AbstractProvider] 准备请求支付服务商 API', $rocket->toArray());

        Event::dispatch(new Event\ApiRequesting($rocket));

        try {
            $response = $http->sendRequest($rocket->getRadar());

            $contents = (string) $response->getBody();

            $rocket->setDestination($response->withBody(Utils::streamFor($contents)))
                ->setDestinationOrigin($response->withBody(Utils::streamFor($contents)));
        } catch (Throwable $e) {
            Logger::error('[AbstractProvider] 请求支付服务商 API 出错', ['message' => $e->getMessage(), 'rocket' => $rocket->toArray(), 'trace' => $e->getTrace()]);

            throw new InvalidResponseException(Exception::REQUEST_RESPONSE_ERROR, $e->getMessage(), [], $e);
        }

        Logger::info('[AbstractProvider] 请求支付服务商 API 成功', ['response' => $response, 'rocket' => $rocket->toArray()]);

        Event::dispatch(new Event\ApiRequested($rocket));

        return $rocket;
    }

    abstract public function mergeCommonPlugins(array $plugins): array;

    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function verifyPlugin(array $plugins): void
    {
        foreach ($plugins as $plugin) {
            if (is_callable($plugin)) {
                continue;
            }

            if ((is_object($plugin) ||
                    (is_string($plugin) && class_exists($plugin))) &&
                in_array(PluginInterface::class, class_implements($plugin))) {
                continue;
            }

            throw new InvalidParamsException(Exception::PLUGIN_ERROR, "[$plugin] is not incompatible");
        }
    }
}

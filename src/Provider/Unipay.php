<?php

declare(strict_types=1);

namespace duan617\Pay\Provider;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use duan617\Pay\Event;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\ParserPlugin;
use duan617\Pay\Plugin\Unipay\CallbackPlugin;
use duan617\Pay\Plugin\Unipay\LaunchPlugin;
use duan617\Pay\Plugin\Unipay\PreparePlugin;
use duan617\Pay\Plugin\Unipay\RadarSignPlugin;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Str;

/**
 * @method ResponseInterface web(array $order) 电脑支付
 */
class Unipay extends AbstractProvider
{
    public const URL = [
        Pay::MODE_NORMAL => 'https://gateway.95516.com/',
        Pay::MODE_SANDBOX => 'https://gateway.test.95516.com/',
        Pay::MODE_SERVICE => 'https://gateway.95516.com',
    ];

    /**
     * @return \Psr\Http\Message\MessageInterface|\Yansongda\Supports\Collection|array|null
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function __call(string $shortcut, array $params)
    {
        $plugin = '\\duan617\\Pay\\Plugin\\Unipay\\Shortcut\\'.
            Str::studly($shortcut).'Shortcut';

        return $this->call($plugin, ...$params);
    }

    /**
     * @param string|array $order
     *
     * @return array|\Yansongda\Supports\Collection
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function find($order)
    {
        if (!is_array($order)) {
            throw new InvalidParamsException(Exception::UNIPAY_FIND_STRING_NOT_SUPPORTED);
        }

        Event::dispatch(new Event\MethodCalled('unipay', __METHOD__, $order, null));

        return $this->__call('query', [$order]);
    }

    /**
     * @param string|array $order
     *
     * @return array|\Yansongda\Supports\Collection
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function cancel($order)
    {
        if (!is_array($order)) {
            throw new InvalidParamsException(Exception::UNIPAY_CANCEL_STRING_NOT_SUPPORTED);
        }

        Event::dispatch(new Event\MethodCalled('unipay', __METHOD__, $order, null));

        return $this->__call('cancel', [$order]);
    }

    /**
     * @param string|array $order
     *
     * @return array|\Yansongda\Supports\Collection
     *
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    public function close($order)
    {
        throw new InvalidParamsException(Exception::METHOD_NOT_SUPPORTED, 'Unipay does not support close api');
    }

    /**
     * @return array|\Yansongda\Supports\Collection
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function refund(array $order)
    {
        Event::dispatch(new Event\MethodCalled('unipay', __METHOD__, $order, null));

        return $this->__call('refund', [$order]);
    }

    /**
     * @param array|\Psr\Http\Message\ServerRequestInterface|null $contents
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    public function callback($contents = null, ?array $params = null): Collection
    {
        Event::dispatch(new Event\CallbackReceived('unipay', $contents, $params, null));

        $request = $this->getCallbackParams($contents);

        return $this->pay(
            [CallbackPlugin::class], $request->merge($params)->all()
        );
    }

    public function success(): ResponseInterface
    {
        return new Response(200, [], 'success');
    }

    public function mergeCommonPlugins(array $plugins): array
    {
        return array_merge(
            [PreparePlugin::class],
            $plugins,
            [RadarSignPlugin::class],
            [LaunchPlugin::class, ParserPlugin::class],
        );
    }

    /**
     * @param array|ServerRequestInterface|null $contents
     */
    protected function getCallbackParams($contents = null): Collection
    {
        if (is_array($contents)) {
            return Collection::wrap($contents);
        }

        if ($contents instanceof ServerRequestInterface) {
            return Collection::wrap($contents->getParsedBody());
        }

        $request = ServerRequest::fromGlobals();

        return Collection::wrap($request->getParsedBody());
    }
}

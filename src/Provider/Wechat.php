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
use duan617\Pay\Plugin\Wechat\CallbackPlugin;
use duan617\Pay\Plugin\Wechat\LaunchPlugin;
use duan617\Pay\Plugin\Wechat\PreparePlugin;
use duan617\Pay\Plugin\Wechat\RadarSignPlugin;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Str;

/**
 * @method Collection app(array $order)  APP 支付
 * @method Collection mini(array $order) 小程序支付
 * @method Collection mp(array $order)   公众号支付
 * @method Collection scan(array $order) 扫码支付
 * @method Collection wap(array $order)  H5 支付
 */
class Wechat extends AbstractProvider
{
    public const AUTH_TAG_LENGTH_BYTE = 16;

    public const MCH_SECRET_KEY_LENGTH_BYTE = 32;

    public const URL = [
        Pay::MODE_NORMAL => 'https://api.mch.weixin.qq.com/',
        Pay::MODE_SANDBOX => 'https://api.mch.weixin.qq.com/sandboxnew/',
        Pay::MODE_SERVICE => 'https://api.mch.weixin.qq.com/',
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
        $plugin = '\\duan617\\Pay\\Plugin\\Wechat\\Shortcut\\'.
            Str::studly($shortcut).'Shortcut';

        return $this->call($plugin, ...$params);
    }

    /**
     * @param array|string $order
     *
     * @return array|\Yansongda\Supports\Collection
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function find($order)
    {
        $order = is_array($order) ? $order : ['transaction_id' => $order];

        Event::dispatch(new Event\MethodCalled('wechat', __METHOD__, $order, null));

        return $this->__call('query', [$order]);
    }

    /**
     * @param array|string $order
     *
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    public function cancel($order): void
    {
        throw new InvalidParamsException(Exception::METHOD_NOT_SUPPORTED, 'Wechat does not support cancel api');
    }

    /**
     * @param array|string $order
     *
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidParamsException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function close($order): void
    {
        $order = is_array($order) ? $order : ['out_trade_no' => $order];

        Event::dispatch(new Event\MethodCalled('wechat', __METHOD__, $order, null));

        $this->__call('close', [$order]);
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
        Event::dispatch(new Event\MethodCalled('wechat', __METHOD__, $order, null));

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
        Event::dispatch(new Event\CallbackReceived('wechat', $contents, $params, null));

        $request = $this->getCallbackParams($contents);

        return $this->pay(
            [CallbackPlugin::class], ['request' => $request, 'params' => $params]
        );
    }

    public function success(): ResponseInterface
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['code' => 'SUCCESS', 'message' => '成功']),
        );
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
    protected function getCallbackParams($contents = null): ServerRequestInterface
    {
        if (is_array($contents) && isset($contents['body']) && isset($contents['headers'])) {
            return new ServerRequest('POST', 'http://localhost', $contents['headers'], $contents['body']);
        }

        if (is_array($contents)) {
            return new ServerRequest('POST', 'http://localhost', [], json_encode($contents));
        }

        if ($contents instanceof ServerRequestInterface) {
            return $contents;
        }

        return ServerRequest::fromGlobals();
    }
}

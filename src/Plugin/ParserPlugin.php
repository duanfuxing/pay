<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin;

use Closure;
use duan617\Pay\Contract\ParserInterface;
use duan617\Pay\Contract\PluginInterface;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidConfigException;
use duan617\Pay\Pay;
use duan617\Pay\Rocket;

class ParserPlugin implements PluginInterface
{
    /**
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        /* @var \Psr\Http\Message\ResponseInterface $response */
        $response = $rocket->getDestination();

        return $rocket->setDestination(
            $this->getPacker($rocket)->parse($response)
        );
    }

    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\InvalidConfigException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    protected function getPacker(Rocket $rocket): ParserInterface
    {
        $packer = Pay::get($rocket->getDirection() ?? ParserInterface::class);

        $packer = is_string($packer) ? Pay::get($packer) : $packer;

        if (!($packer instanceof ParserInterface)) {
            throw new InvalidConfigException(Exception::INVALID_PACKER);
        }

        return $packer;
    }
}

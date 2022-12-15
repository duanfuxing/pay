<?php

declare(strict_types=1);

namespace duan617\Pay\Plugin\Wechat\Pay\Common;

use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Parser\OriginResponseParser;
use duan617\Pay\Plugin\Wechat\GeneralPlugin;
use duan617\Pay\Rocket;

class DownloadBillPlugin extends GeneralPlugin
{
    /**
     * @throws \duan617\Pay\Exception\InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (is_null($payload->get('download_url'))) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return $payload->get('download_url');
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setDirection(OriginResponseParser::class);

        $rocket->setPayload(null);
    }
}

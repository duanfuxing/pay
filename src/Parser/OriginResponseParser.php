<?php

declare(strict_types=1);

namespace duan617\Pay\Parser;

use Psr\Http\Message\ResponseInterface;
use duan617\Pay\Contract\ParserInterface;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidResponseException;

class OriginResponseParser implements ParserInterface
{
    /**
     * @throws \duan617\Pay\Exception\InvalidResponseException
     */
    public function parse(?ResponseInterface $response): ?ResponseInterface
    {
        if (!is_null($response)) {
            return $response;
        }

        throw new InvalidResponseException(Exception::INVALID_RESPONSE_CODE);
    }
}

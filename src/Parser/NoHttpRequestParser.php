<?php

declare(strict_types=1);

namespace duan617\Pay\Parser;

use Psr\Http\Message\ResponseInterface;
use duan617\Pay\Contract\ParserInterface;

class NoHttpRequestParser implements ParserInterface
{
    public function parse(?ResponseInterface $response): ?ResponseInterface
    {
        return $response;
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Parser;

use Psr\Http\Message\ResponseInterface;
use duan617\Pay\Contract\ParserInterface;
use duan617\Pay\Pay;
use Yansongda\Supports\Collection;

class CollectionParser implements ParserInterface
{
    /**
     * @throws \duan617\Pay\Exception\ContainerException
     * @throws \duan617\Pay\Exception\ServiceNotFoundException
     */
    public function parse(?ResponseInterface $response): Collection
    {
        return new Collection(
            Pay::get(ArrayParser::class)->parse($response)
        );
    }
}

<?php

declare(strict_types=1);

namespace duan617\Pay\Contract;

use Psr\Http\Message\ResponseInterface;

interface ParserInterface
{
    /**
     * @return mixed
     */
    public function parse(?ResponseInterface $response);
}

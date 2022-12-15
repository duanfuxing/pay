<?php

declare(strict_types=1);

namespace duan617\Pay\Parser;

use Psr\Http\Message\ResponseInterface;
use duan617\Pay\Contract\ParserInterface;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidResponseException;
use Yansongda\Supports\Str;

class ArrayParser implements ParserInterface
{
    /**
     * @throws \duan617\Pay\Exception\InvalidResponseException
     */
    public function parse(?ResponseInterface $response): array
    {
        if (is_null($response)) {
            throw new InvalidResponseException(Exception::RESPONSE_NONE);
        }

        $body = (string) $response->getBody();

        $result = json_decode($body, true);
        if (JSON_ERROR_NONE === json_last_error()) {
            return $result;
        }

        if (Str::contains($body, '&')) {
            return $this->query($body);
        }

        throw new InvalidResponseException(Exception::UNPACK_RESPONSE_ERROR, 'Unpack Response Error', ['body' => $body, 'response' => $response]);
    }

    protected function query(string $body): array
    {
        $result = [];

        foreach (explode('&', $body) as $item) {
            $pos = strpos($item, '=');

            $result[substr($item, 0, $pos)] = substr($item, $pos + 1);
        }

        return $result;
    }
}

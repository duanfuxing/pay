<?php

declare(strict_types=1);

namespace duan617\Pay\Event;

use duan617\Pay\Rocket;

class Event
{
    public ?Rocket $rocket = null;

    public function __construct(?Rocket $rocket = null)
    {
        $this->rocket = $rocket;
    }
}

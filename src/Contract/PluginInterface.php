<?php

declare(strict_types=1);

namespace duan617\Pay\Contract;

use Closure;
use duan617\Pay\Rocket;

interface PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket;
}

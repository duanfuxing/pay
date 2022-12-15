<?php

declare(strict_types=1);

namespace duan617\Pay\Event;

use duan617\Pay\Rocket;

class PayStarted extends Event
{
    /**
     * @var \duan617\Pay\Contract\PluginInterface[]
     */
    public array $plugins;

    public array $params;

    public function __construct(array $plugins, array $params, ?Rocket $rocket = null)
    {
        $this->plugins = $plugins;
        $this->params = $params;

        parent::__construct($rocket);
    }
}

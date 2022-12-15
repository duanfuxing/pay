<?php

declare(strict_types=1);

namespace duan617\Pay\Contract;

interface ShortcutInterface
{
    /**
     * @return \duan617\Pay\Contract\PluginInterface[]|string[]
     */
    public function getPlugins(array $params): array;
}

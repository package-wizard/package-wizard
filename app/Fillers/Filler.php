<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers;

use function app;

abstract class Filler
{
    abstract public function get(): mixed;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function make(...$parameters): mixed
    {
        return app(static::class, $parameters)->get();
    }
}

<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Support;

use function app;

class Console
{
    public bool $ansi;

    public bool $verbose;

    public static function ansi(): bool
    {
        return app(static::class)->ansi;
    }

    public static function quiet(): bool
    {
        return ! static::verbose();
    }

    public static function verbose(): bool
    {
        return app(static::class)->verbose;
    }

    public static function setAnsi(bool $enabled): void
    {
        app(static::class)->ansi = $enabled;
    }

    public static function setVerbose(bool $enabled): void
    {
        app(static::class)->verbose = $enabled;
    }
}

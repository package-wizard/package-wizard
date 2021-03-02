<?php

namespace Helldar\PackageWizard\Facades;

use Composer\IO\IOInterface;
use Helldar\PackageWizard\Services\Logger as Service;
use Helldar\Support\Facades\BaseFacade;

/**
 * @method static void set(IOInterface $io)
 * @method static void write(array $messages)
 */
final class Logger extends BaseFacade
{
    protected static function getFacadeAccessor()
    {
        return Service::class;
    }
}

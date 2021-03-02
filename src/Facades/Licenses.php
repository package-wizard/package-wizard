<?php

namespace Helldar\PackageWizard\Facades;

use Helldar\PackageWizard\Services\Licenses as Service;
use Helldar\Support\Facades\BaseFacade;

/**
 * @method static array available()
 * @method static string|null get(int $index)
 * @method static array all()
 * @method static string getDefault()
 */
final class Licenses extends BaseFacade
{
    protected static function getFacadeAccessor()
    {
        return Service::class;
    }
}

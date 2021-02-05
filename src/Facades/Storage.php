<?php

namespace Helldar\PackageWizard\Facades;

use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Services\Storage as Support;
use Helldar\Support\Facades\BaseFacade;

/**
 * @method static Support stepper(Stepperable $stepper)
 * @method static void store()
 */
final class Storage extends BaseFacade
{
    protected static function getFacadeAccessor()
    {
        return Support::class;
    }
}

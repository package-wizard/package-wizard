<?php

namespace Helldar\PackageWizard\Providers;

use Composer\Plugin\Capability\CommandProvider;
use Helldar\PackageWizard\Command\Wizard;

final class Command implements CommandProvider
{
    public function getCommands(): array
    {
        return [
            new Wizard(),
        ];
    }
}

<?php

namespace Helldar\PackageWizard;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Helldar\PackageWizard\Providers\Command;

final class Application implements PluginInterface, Capable
{
    public function getCapabilities()
    {
        return [
            CommandProvider::class => Command::class,
        ];
    }

    public function activate(Composer $composer, IOInterface $io)
    {
        // nothing
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // nothing
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // nothing
    }
}

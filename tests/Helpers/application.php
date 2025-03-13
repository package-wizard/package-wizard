<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;
use Symfony\Component\Console\Application;

function createApplication(): Application
{
    $app = new Application();

    $app->add(new NewCommand());

    return $app;
}

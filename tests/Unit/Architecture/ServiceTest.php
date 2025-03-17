<?php

declare(strict_types=1);

use PackageWizard\Installer\Services\Managers\Manager;

arch()
    ->expect('App\Services')
    ->toHaveSuffix('Service')
    ->ignoring(Manager::class);

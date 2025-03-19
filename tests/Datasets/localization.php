<?php

declare(strict_types=1);

use DragonCode\Support\Facades\Filesystem\Directory;

use function PackageWizard\Installer\base_path;

dataset('localization', Directory::names(base_path('lang')));

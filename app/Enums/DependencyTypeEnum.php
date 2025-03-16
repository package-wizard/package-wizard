<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Enums;

enum DependencyTypeEnum: string
{
    case Composer = 'composer';
    case Npm      = 'npm';
    case Yarn     = 'yarn';
}

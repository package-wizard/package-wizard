<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Enums;

enum TypeEnum: string
{
    case Year      = 'year';
    case YearRange = 'yearRange';
    case Date      = 'date';
    case License   = 'license';
}

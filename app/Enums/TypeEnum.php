<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Enums;

enum TypeEnum: string
{
    case Ask       = 'ask';
    case Author    = 'author';
    case Date      = 'date';
    case License   = 'license';
    case Year      = 'year';
    case YearRange = 'yearRange';
}

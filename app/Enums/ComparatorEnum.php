<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Enums;

enum ComparatorEnum: string
{
    case LessThan             = 'lt';
    case LessThanOrEqualTo    = 'lte';
    case EqualTo              = 'eq';
    case NotEqualTo           = 'neq';
    case GreaterThan          = 'gt';
    case GreaterThanOrEqualTo = 'gte';
    case InList               = 'il';
    case NotInList            = 'nil';
    case Contains             = 'ct';
    case DoesntContain        = 'dc';
    case ContainsAll          = 'cta';
    case DoesntContainAll     = 'dca';
}

<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Enums;

enum ComparatorEnum: string
{
    case LessThan             = 'lessThan';
    case LessThanOrEqualTo    = 'lessThanOrEqualTo';
    case EqualTo              = 'equalTo';
    case NotEqualTo           = 'notEqualTo';
    case GreaterThan          = 'greaterThan';
    case GreaterThanOrEqualTo = 'greaterThanOrEqualTo';
    case InList               = 'inList';
    case NotInList            = 'notInList';
    case Contains             = 'contains';
    case DoesntContain        = 'doesntContain';
    case ContainsAll          = 'containsAll';
    case DoesntContainAll     = 'doesntContainAll';
}

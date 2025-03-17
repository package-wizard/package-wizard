<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Enums;

enum ComparatorEnum: string
{
    case LessThan             = '<';
    case LessThanOrEqualTo    = '<=';
    case EqualTo              = '=';
    case NotEqualTo           = '!=';
    case GreaterThan          = '>';
    case GreaterThanOrEqualTo = '>=';
    case InList               = 'in';
    case NotInList            = 'notIn';
    case Contains             = 'contains';
    case DoesntContain        = 'doesntContain';
    case ContainsAll          = 'containsAll';
    case DoesntContainAll     = 'doesntContainAll';
    case ExistsPath           = 'pathExists';
    case DoesNotExistPath     = 'pathDoesNotExist';
}

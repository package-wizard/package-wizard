<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Enums;

enum ConditionOperatorEnum: string
{
    case LessThan             = '<';
    case LessThanOrEqualTo    = '<=';
    case EqualTo              = '=';
    case NotEqualTo           = '!=';
    case GreaterThan          = '>';
    case GreaterThanOrEqualTo = '>=';
    case In                   = 'in';
    case NotIn                = 'notIn';
    case Contains             = 'contains';
    case DoesntContain        = 'doesntContain';
    case ContainsAll          = 'containsAll';
    case DoesntContainAll     = 'doesntContainAll';
    case PathExists           = 'pathExists';
    case PathDoesNotExist     = 'pathDoesNotExist';
}

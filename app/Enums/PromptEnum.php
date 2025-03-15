<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Enums;

enum PromptEnum: string
{
    case Text   = 'text';
    case Select = 'select';
}

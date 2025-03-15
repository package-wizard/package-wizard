<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Spatie\LaravelData\Data;

class AuthorData extends Data
{
    public ?string $name = null;

    public ?string $email = null;
}

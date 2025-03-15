<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class AuthorData extends Data
{
    public Optional|string $name;

    public Optional|string $email;

    public Optional|string $homepage;
}

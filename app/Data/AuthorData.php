<?php

namespace PackageWizard\Installer\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class AuthorData extends Data
{
    public Optional|string $name;

    public Optional|string $email;
}

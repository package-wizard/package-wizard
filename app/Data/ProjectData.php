<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProjectData extends Data
{
    public ?string $directory = null;

    public Optional|string $url;

    public Optional|string $title;

    public Optional|string $description;
}

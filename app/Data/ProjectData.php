<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Spatie\LaravelData\Data;

class ProjectData extends Data
{
    public ?string $directory = null;

    public ?string $url = null;

    public ?string $title = null;

    public ?string $description = null;
}

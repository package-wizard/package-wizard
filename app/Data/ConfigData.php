<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Illuminate\Support\Collection;
use PackageWizard\Installer\Data\Casts\QuestionsCast;
use PackageWizard\Installer\Data\Casts\VariablesCast;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ConfigData extends Data
{
    public string $schema = 'https://package-wizard.com/schemas/schema-v2.json';

    public WizardData $wizard;

    public ProjectData $project;

    #[DataCollectionOf(AuthorData::class)]
    public Collection|Optional $authors;

    #[WithCast(VariablesCast::class)]
    public Collection|Optional $variables;

    #[WithCast(QuestionsCast::class)]
    public Collection|Optional $questions;

    #[DataCollectionOf(ReplaceData::class)]
    public Collection|Optional $replaces;
}

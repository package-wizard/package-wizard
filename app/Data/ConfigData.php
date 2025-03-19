<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Illuminate\Support\Collection;
use PackageWizard\Installer\Data\Casts\QuestionsCast;
use PackageWizard\Installer\Data\Casts\VariablesCast;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class ConfigData extends Data
{
    public string $schema;

    public ?string $directory = null;

    public WizardData $wizard;

    #[DataCollectionOf(AuthorData::class)]
    public Collection $authors;

    #[WithCast(VariablesCast::class)]
    public Collection $variables;

    #[DataCollectionOf(RenameData::class)]
    public Collection $renames;

    #[DataCollectionOf(CopyData::class)]
    public Collection $copies;

    public Collection $removes;

    #[DataCollectionOf(ReplaceData::class)]
    public Collection $replaces;

    #[DataCollectionOf(DependencyData::class)]
    public Collection $dependencies;

    #[WithCast(QuestionsCast::class)]
    public Collection $questions;

    public static function prepareForPipeline(array $properties): array
    {
        $properties['wizard']       ??= [];
        $properties['authors']      ??= [];
        $properties['variables']    ??= [];
        $properties['renames']      ??= [];
        $properties['copies']       ??= [];
        $properties['removes']      ??= [];
        $properties['replaces']     ??= [];
        $properties['dependencies'] ??= [];
        $properties['questions']    ??= [];

        return $properties;
    }
}

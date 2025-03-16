<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Illuminate\Support\Collection;
use PackageWizard\Installer\Data\Casts\QuestionsCast;
use PackageWizard\Installer\Data\Casts\VariablesCast;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\ProvidedNameMapper;

// TODO: copies + questions
// TODO: conditions
class ConfigData extends Data
{
    #[MapName(new ProvidedNameMapper('$schema'))]
    public string $schema = 'https://package-wizard.com/schemas/schema-v2.json';

    public WizardData $wizard;

    #[DataCollectionOf(AuthorData::class)]
    public Collection $authors;

    #[WithCast(VariablesCast::class)]
    public Collection $variables;

    #[DataCollectionOf(RenameData::class)]
    public Collection $renames;

    #[DataCollectionOf(ReplaceData::class)]
    public Collection $replaces;
    
    public Collection $removes;

    #[WithCast(QuestionsCast::class)]
    public Collection $questions;

    public static function prepareForPipeline(array $properties): array
    {
        $properties['wizard']    ??= [];
        $properties['authors']   ??= [];
        $properties['variables'] ??= [];
        $properties['renames']   ??= [];
        $properties['replaces']  ??= [];
        $properties['questions'] ??= [];

        return $properties;
    }
}

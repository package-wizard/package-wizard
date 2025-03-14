<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers;

use function is_file;
use function Laravel\Prompts\text;

/** @method static make(string $name) */
class ProjectPathFiller extends Filler
{
    public function __construct(
        protected string $name
    ) {}

    public function get(): string
    {
        return text(
            label    : 'Specify the path to the folder where the project will be loaded:',
            required : true,
            validate : fn (string $path) => match (true) {
                is_file($path) => 'The specified path must not reference a file.',
                default        => null
            },
            hint     : 'For example, blog',
            transform: fn (string $value) => trim($value)
        );
    }
}

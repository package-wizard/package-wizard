<?php

namespace Helldar\PackageWizard\Structures;

use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Contracts\Structurable;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Filesystem\Directory;
use Helldar\Support\Facades\Helpers\Str;

abstract class BaseStructure implements Structurable
{
    use Makeable;

    protected Stepperable $stepper;

    protected array $directories = [
        'src',
        'tests',
    ];

    protected array $basic_files = [
        '.codecov.yml',
        '.editorconfig',
        '.gitattributes',
        '.gitignore',
        '.styleci.yml',
        'phpunit.xml',
    ];

    protected string $resource_path = __DIR__ . '/../../resources/';

    protected ?string $target_path;

    /** @var \Helldar\PackageWizard\Resources\BaseResource[]|array */
    protected array $resources = [];

    public function __construct(Stepperable $stepper)
    {
        $this->stepper = $stepper;
    }

    public function path(string $path): Structurable
    {
        $path = rtrim(realpath($path), '/\\');

        Directory::validate($path);

        $this->target_path = Str::finish($path);

        return $this;
    }

    protected function run(): void
    {
        $this->copyBasics();
        $this->copyResources();
    }

    protected function copyBasics(): void
    {
        foreach ($this->basic_files as $filename) {
            $this->copy($filename);
        }
    }

    protected function copyResources(): void
    {
        foreach ($this->resources as $resource) {

        }
    }

    protected function copy(string $filename): void
    {
        copy($this->resource_path . $filename, $this->target_path . $filename);
    }
}

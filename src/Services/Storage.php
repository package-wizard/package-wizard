<?php

namespace Helldar\PackageWizard\Services;

use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Resources\License;
use Helldar\PackageWizard\Resources\Readme;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Arr;
use Helldar\Support\Facades\Helpers\Filesystem\Directory;
use Helldar\Support\Facades\Helpers\Filesystem\File;
use Helldar\Support\Facades\Helpers\Str;

final class Storage
{
    use Makeable;

    /** @var string */
    protected $base_path;

    /** @var \Helldar\PackageWizard\Contracts\Stepperable */
    protected $stepper;

    public function basePath(string $path): self
    {
        $this->base_path = $path;

        return $this;
    }

    public function stepper(Stepperable $stepper): self
    {
        $this->stepper = $stepper;

        return $this;
    }

    public function store(): void
    {
        $this->basic();
        $this->composerJson();
        $this->license();
        $this->readme();
        $this->source();
        $this->tests();
    }

    protected function composerJson(): void
    {
        Arr::storeAsJson($this->path('composer.json'), $this->printData(), false, JSON_PRETTY_PRINT);
    }

    protected function source(): void
    {
        if ($path = $this->stepper->getAutoloadPath()) {
            Directory::make($this->path($path));
        }
    }

    protected function tests(): void
    {
        Directory::make($this->path('tests'));
    }

    protected function license(): void
    {
        if ($license = $this->stepper->getLicense()) {
            $parser = Parser::make();

            $authors = array_map(static function ($values) {
                return Arr::get($values, 'name');
            }, $this->stepper->getAuthors());

            $content = License::make()
                ->parser($parser)
                ->license($license)
                ->authors($authors)
                ->toString();

            File::store($this->path('LICENSE'), $content);
        }
    }

    protected function readme(): void
    {
        $parser = Parser::make();

        $name        = $this->stepper->getName();
        $description = $this->stepper->getDescription();

        $title = Str::studly(Str::after($name, '/'));

        $content = Readme::make()
            ->parser($parser)
            ->replaces(compact('name', 'title', 'description'))
            ->toString();

        File::store($this->path('README.md'), $content);
    }

    protected function basic(): void
    {
        copy($this->resourcesPath('.codecov.yml'), $this->path('.codecov.yml'));
        copy($this->resourcesPath('.editorconfig'), $this->path('.editorconfig'));
        copy($this->resourcesPath('.gitattributes'), $this->path('.gitattributes'));
        copy($this->resourcesPath('.gitignore'), $this->path('.gitignore'));
        copy($this->resourcesPath('.styleci.yml'), $this->path('.styleci.yml'));
        copy($this->resourcesPath('phpunit.xml'), $this->path('phpunit.xml'));
    }

    protected function path(string $path): string
    {
        return rtrim($this->base_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path;
    }

    protected function resourcesPath(string $filename): string
    {
        return realpath(__DIR__ . '/../../resources/' . $filename);
    }

    protected function printData(): array
    {
        return array_filter($this->stepper->toArray(), static function ($value) {
            return ! empty($value) || is_bool($value) || is_numeric($value);
        });
    }
}

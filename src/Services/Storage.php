<?php

namespace Helldar\PackageWizard\Services;

use Composer\Factory;
use Composer\Json\JsonFile;
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

    /** @var \Helldar\PackageWizard\Contracts\Stepperable */
    protected Stepperable $stepper;

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
        $file = new JsonFile(Factory::getComposerFile());

        $file->write($this->printData());
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

        $title = Str::after($name, '/');
        $title = Str::snake(Str::camel($title));
        $title = Str::title(str_replace('_', ' ', $title));

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
        return realpath('.') . '/' . trim($path, '/\\');
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

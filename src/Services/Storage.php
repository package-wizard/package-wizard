<?php

namespace Helldar\PackageWizard\Services;

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

    /** @var \Helldar\PackageWizard\Services\Structure */
    protected $structure;

    public function basePath(string $path): self
    {
        $this->base_path = $path;

        return $this;
    }

    public function structure(Structure $structure): self
    {
        $this->structure = $structure;

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
        Arr::storeAsJson($this->path('composer.json'), $this->structure->toArray(), false, JSON_PRETTY_PRINT);
    }

    protected function source(): void
    {
        Directory::make($this->path('source'));
    }

    protected function tests(): void
    {
        if ($this->structure->hasTests()) {
            Directory::make($this->path('tests'));
        }
    }

    protected function license(): void
    {
        if ($license = $this->structure->getLicense()) {
            $template = file_get_contents($this->resourcesPath('licenses/' . $license));

            $authors = Arr::only($this->structure->getAuthors(), ['name']);

            $content = Parser::make()
                ->template($template)
                ->replace('year', date('Y'))
                ->replace('authors', implode(', ', $authors))
                ->get();

            File::store($this->path('LICENSE'), $content);
        }
    }

    protected function readme(): void
    {
        $template = file_get_contents($this->resourcesPath('README.md'));

        $name        = $this->structure->getName();
        $description = $this->structure->getDescription();

        $title = Str::studly(Str::after($name, '/'));

        $content = Parser::make()
            ->template($template)
            ->many(compact('name', 'title', 'description'))
            ->get();

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
}

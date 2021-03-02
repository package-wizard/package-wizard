<?php

namespace Helldar\PackageWizard\Services;

use Composer\Factory;
use Composer\Json\JsonFile;
use Helldar\PackageWizard\Concerns\Logger;
use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Resources\BaseResource;
use Helldar\PackageWizard\Resources\Custom;
use Helldar\PackageWizard\Resources\License;
use Helldar\PackageWizard\Resources\Readme;
use Helldar\Support\Facades\Helpers\Filesystem\Directory;
use Helldar\Support\Facades\Helpers\Filesystem\File;
use Helldar\Support\Facades\Helpers\Is;
use Helldar\Support\Facades\Helpers\Str;
use Symfony\Component\Finder\Finder;

final class Storage
{
    use Logger;

    /** @var \Helldar\PackageWizard\Contracts\Stepperable */
    protected $stepper;

    protected $base_resources = [
        License::class => 'LICENSE',
        Readme::class  => 'README.md',
    ];

    protected $basic_files = [
        '.codecov.yml',
        '.editorconfig',
        '.gitattributes',
        '.gitignore',
        '.styleci.yml',
        'phpunit.xml',
    ];

    protected $replaces = [];

    public function stepper(Stepperable $stepper): self
    {
        $this->stepper = $stepper;

        return $this;
    }

    public function store(): void
    {
        $this->log('Start saving files');

        $this->basicFiles();
        $this->composerJson();
        $this->resources();
        $this->source();
        $this->structures();
        $this->tests();
    }

    protected function composerJson(): void
    {
        $this->log('Save the data to the composer.json file');

        $file = new JsonFile(Factory::getComposerFile());

        $file->write($this->printData());
    }

    protected function source(): void
    {
        if ($path = $this->stepper->getAutoloadPath()) {
            $path = $this->path($path);

            $this->log('Create a folder:', $path);

            Directory::make($path);
        }
    }

    protected function tests(): void
    {
        $path = $this->path('tests');

        $this->log('Create a tests folder:', $path);

        Directory::make($path);
    }

    protected function resources(): void
    {
        foreach ($this->base_resources as $resource => $filename) {
            $this->log('Save the "', $resource, '" resource to the "', $filename, '" file');

            $this->save($filename, $this->resource($resource));
        }
    }

    /**
     * @param  \Helldar\PackageWizard\Resources\BaseResource|string  $resource
     *
     * @return \Helldar\PackageWizard\Resources\BaseResource
     */
    protected function resource(string $resource): BaseResource
    {
        $this->log('Retrieving a "', $resource, '" resource instance with set values');

        return $resource::make()
            ->stepper($this->stepper)
            ->parser($this->parser());
    }

    protected function basicFiles(): void
    {
        foreach ($this->basic_files as $filename) {
            $this->log('Copy the base file:', $filename);

            $this->copy($filename);
        }
    }

    protected function structures(): void
    {
        $name = $this->resolveStructureName();

        $path = $this->resourcesPath('stubs/' . $name, false);

        $real_path = realpath($path);

        $replaces = $this->getReplaces();

        $this->log('Get a list of files of the main structure:', $name, '(' . $real_path . ')');

        foreach ($this->files($path) as $item) {
            $relative = Str::after($item->getRealPath(), $real_path);

            $relative = str_replace(array_keys($replaces), array_values($replaces), $relative);

            $relative = trim($relative, '/\\');

            $this->structure($item->getRealPath(), $relative);
        }
    }

    protected function structure(string $source_path, string $target_path): void
    {
        $content = $this->resource(Custom::class)->setPath($source_path);

        $this->log('Copying structure file (', Custom::class, ') from "', $source_path, '" to "', $target_path, '"');

        $this->save($target_path, $content);
    }

    protected function printData(): array
    {
        $this->log('Getting data to save to composer.json file');

        return array_filter($this->stepper->toArray(), static function ($value) {
            return Is::doesntEmpty($value);
        });
    }

    protected function parser(): Parser
    {
        return Parser::make();
    }

    protected function copy(string $filename): void
    {
        $source = $this->stubFilename($filename);
        $target = $this->nativeFilename($filename);

        $this->log('Copy the "', $filename, '" from "', $source, '" to "', $target, '"');

        copy($this->resourcesPath($source), $this->path($target));
    }

    protected function save(string $filename, BaseResource $resource): void
    {
        $filename = $this->nativeFilename($filename);

        $this->log('Saving the "', $resource, '" to the "', $filename, '" file');

        File::store($this->path($filename), $resource->toString());
    }

    protected function path(string $path): string
    {
        return realpath('.') . '/' . trim($path, '/\\');
    }

    protected function resolveStructureName(): string
    {
        $class = get_class($this->stepper);

        $basename = basename(str_replace('\\', '/', $class));

        $snake = Str::snake($basename, '-');

        return Str::lower($snake);
    }

    protected function resourcesPath(string $filename, bool $use_real = true): string
    {
        $path = rtrim(__DIR__ . '/../../resources', '/\\') . '/' . $filename;

        $this->log('Getting the path to the resource file:', $filename, '(relative path is:', $path, ', real path is:', realpath($path), ')');

        return $use_real ? realpath($path) : $path;
    }

    protected function stubFilename(string $filename): string
    {
        $stub = Str::finish($filename, '.stub');

        $path = File::exists($this->resourcesPath($stub)) ? $stub : $filename;

        $this->log('Getting the path to the template file:', $stub, '(', $path, ')');

        return $path;
    }

    protected function nativeFilename(string $filename): string
    {
        $native = str_replace('.stub', '', $filename);

        $this->log('Bringing the file name to its native form:', $filename, '(', $native, ')');

        return $native;
    }

    /**
     * @param  string  $path
     *
     * @return array|\SplFileInfo[]
     */
    protected function files(string $path): array
    {
        $real = realpath($path);

        if (Directory::doesntExist($real)) {
            $this->log('Directory "', $path, '" does not exist (real path is: ' . $real . ')');

            return [];
        }

        $this->log('Getting files from a directory:', $path);

        return iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(false)->in($path)->sortByName(),
            false
        );
    }

    protected function getReplaces(): array
    {
        if (! empty($this->replaces)) {
            return $this->replaces;
        }

        return $this->replaces = [
            'config.php' => Str::after($this->stepper->getName(), '/') . '.php',
        ];
    }
}

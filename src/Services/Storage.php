<?php

namespace Helldar\PackageWizard\Services;

use Composer\Factory;
use Composer\Json\JsonFile;
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
    /** @var \Helldar\PackageWizard\Contracts\Stepperable */
    protected Stepperable $stepper;

    protected array $base_resources = [
        License::class => 'LICENSE',
        Readme::class  => 'README.md',
    ];

    protected array $basic_files = [
        '.codecov.yml.stub',
        '.editorconfig.stub',
        '.gitattributes.stub',
        '.gitignore.stub',
        '.styleci.yml.stub',
        'phpunit.xml.stub',
    ];

    protected array $replaces = [];

    public function stepper(Stepperable $stepper): self
    {
        $this->stepper = $stepper;

        return $this;
    }

    public function store(): void
    {
        $this->basicFiles();
        $this->composerJson();
        $this->resources();
        $this->source();
        $this->structures();
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

    protected function resources(): void
    {
        foreach ($this->base_resources as $resource => $filename) {
            $this->save($filename, $this->resource($resource));
        }
    }

    /**
     * @param  \Helldar\PackageWizard\Resources\BaseResource|string  $resource
     *
     * @return string
     */
    protected function resource(string $resource): BaseResource
    {
        return $resource::make()
            ->stepper($this->stepper)
            ->parser($this->parser());
    }

    protected function basicFiles(): void
    {
        foreach ($this->basic_files as $filename) {
            $this->copy($filename);
        }
    }

    protected function structures(): void
    {
        $name = $this->resolveStructureName();

        $path = $this->resourcesPath('stubs/' . $name);

        $replaces = $this->getReplaces();

        foreach ($this->files($path) as $item) {
            $relative = Str::after($item->getRealPath(), $path);
            $relative = str_replace('.stub', '', $relative);
            $relative = str_replace(array_keys($replaces), array_values($replaces), $relative);
            $relative = trim($relative, '/\\');

            $this->structure($item->getRealPath(), $relative);
        }
    }

    protected function structure(string $source_path, string $target_path): void
    {
        $content = $this->resource(Custom::class)->setPath($source_path);

        $this->save($target_path, $content);
    }

    protected function printData(): array
    {
        return array_filter($this->stepper->toArray(), static fn ($value) => Is::doesntEmpty($value));
    }

    protected function parser(): Parser
    {
        return Parser::make();
    }

    protected function copy(string $filename): void
    {
        $target_file = Str::endsWith($filename, '.stub') ? pathinfo($filename, PATHINFO_FILENAME) : $filename;

        copy($this->resourcesPath($filename), $this->path($target_file));
    }

    protected function save(string $filename, BaseResource $resource): void
    {
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

    protected function resourcesPath(string $filename): string
    {
        return realpath(rtrim(__DIR__ . '/../../resources', '/\\') . '/' . $filename);
    }

    /**
     * @param  string  $path
     *
     * @return array|\SplFileInfo[]
     */
    protected function files(string $path): array
    {
        if (Directory::doesntExist($path)) {
            return [];
        }

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
            'config.php' => Str::after($this->stepper->getName(), '/'),
        ];
    }
}

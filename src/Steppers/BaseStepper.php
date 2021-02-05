<?php

namespace Helldar\PackageWizard\Steppers;

use Helldar\PackageWizard\Constants\Steps;
use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Services\Namespacing;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Arr;
use Helldar\Support\Facades\Helpers\Http;
use Helldar\Support\Facades\Helpers\Str;

abstract class BaseStepper implements Stepperable
{
    use Makeable;

    protected string $name;

    protected string $description;

    protected string $type = 'library';

    protected string $license;

    protected array $keywords = [];

    protected array $authors = [];

    protected array $support = [];

    protected array $require = [
        'php' => '^8.0',
    ];

    protected array $require_dev = [
        'mockery/mockery' => '^1.0',
        'phpunit/phpunit' => '^9.0',
    ];

    protected array $autoload = [];

    protected array $autoload_dev = [
        'psr-4' => [
            'Tests\\' => 'tests',
        ],
    ];

    protected string $autoload_path = 'src';

    protected array $config = [
        'preferred-install' => 'dist',
        'sort-packages'     => true,
    ];

    protected string $minimum_stability = 'stable';

    protected bool $prefer_stable = true;

    protected array $extra = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLicense(): string
    {
        return $this->license;
    }

    public function setLicense(string $license): void
    {
        $this->license = $license;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function setKeywords(array $keywords): void
    {
        $this->keywords = $keywords;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): void
    {
        $this->authors = array_map(static function ($author) {
            [$name, $email] = $author;

            return compact('name', 'email');
        }, $authors);
    }

    public function pushAuthor(array $author): void
    {
        $name  = $author['name'] ?? $author[0];
        $email = $author['email'] ?? $author[1];

        $this->setAuthors([[$name, $email]]);
    }

    public function getSupport(): array
    {
        return $this->support;
    }

    public function getRequire(): array
    {
        return $this->require;
    }

    public function setRequire(array $dependencies): void
    {
        $this->filterDependencies($dependencies, $this->require, $this->require_dev, ['mockery/', 'phpunit/', 'composer/', 'orchestra/', 'symfony/thanks']);
    }

    public function getRequireDev(): array
    {
        return $this->require_dev;
    }

    public function setRequireDev(array $dependencies): void
    {
        $this->filterDependencies($dependencies, $this->require_dev, $this->require, ['php']);
    }

    public function getAutoload(): array
    {
        $this->autoload['psr-4'] = [$this->getNamespace() => $this->autoload_path];

        return $this->autoload;
    }

    public function getAutoloadDev(): array
    {
        return $this->autoload_dev;
    }

    public function getAutoloadPath(): ?string
    {
        return $this->autoload_path ?: null;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getMinimumStability(): string
    {
        return $this->minimum_stability;
    }

    public function isPreferStable(): bool
    {
        return $this->prefer_stable;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function setRepositoryUrl(string $url): void
    {
        $url = Http::validatedUrl(trim($url, " \t\n\r\0\x0B/"));

        $this->support['issues'] = Str::finish($url, '/issues');
        $this->support['source'] = $url;
    }

    public function getNamespace(): string
    {
        return Namespacing::make($this->getName())->get();
    }

    public function steps(): array
    {
        return [
            Steps::NAME,
            Steps::DESCRIPTION,
            Steps::LICENSE,
            Steps::KEYWORDS,
            Steps::AUTHORS,
            Steps::REPOSITORY_URL,
            Steps::REQUIRE,
            Steps::REQUIRE_DEV,
        ];
    }

    public function toArray(): array
    {
        return [
            'name'              => $this->getName(),
            'description'       => $this->getDescription(),
            'type'              => $this->getType(),
            'license'           => $this->getLicense(),
            'keywords'          => $this->getKeywords(),
            'authors'           => $this->getAuthors(),
            'support'           => $this->getSupport(),
            'require'           => $this->getRequire(),
            'require-dev'       => $this->getRequireDev(),
            'autoload'          => $this->getAutoload(),
            'autoload-dev'      => $this->getAutoloadDev(),
            'config'            => $this->getConfig(),
            'minimum-stability' => $this->getMinimumStability(),
            'prefer-stable'     => $this->isPreferStable(),
            'extra'             => $this->getExtra(),
        ];
    }

    protected function filterDependencies(array $dependencies, array &$target, array &$fallback, array $except_packages): void
    {
        $only   = Arr::only($dependencies, static fn ($key) => ! in_array($key, $except_packages) && ! Str::startsWith($key, $except_packages));
        $except = Arr::only($dependencies, static fn ($key) => in_array($key, $except_packages) || Str::startsWith($key, $except_packages));

        $target   = array_merge($target, $only);
        $fallback = array_merge($fallback, $except);
    }
}

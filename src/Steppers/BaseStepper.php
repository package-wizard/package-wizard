<?php

namespace Helldar\PackageWizard\Steppers;

use Helldar\PackageWizard\Constants\Steps;
use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Http;
use Helldar\Support\Facades\Helpers\Str;

abstract class BaseStepper implements Stepperable
{
    use Makeable;

    protected $name;

    protected $description;

    protected $type = 'library';

    protected $license;

    protected $keywords = [];

    protected $authors = [];

    protected $support = [];

    protected $require = [];

    protected $require_dev = [];

    protected $autoload = [];

    protected $autoload_dev = [
        'psr-4' => [
            'Tests\\' => 'tests',
        ],
    ];

    protected $autoload_path = 'src';

    protected $config = [
        'preferred-install' => 'dist',
        'sort-packages'     => true,
    ];

    protected $minimum_stability = 'stable';

    protected $prefer_stable = true;

    protected $extra = [];

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
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
        $this->authors = $authors;
    }

    public function getSupport(): array
    {
        return $this->support;
    }

    public function getRequire(): array
    {
        return $this->require;
    }

    public function setRequire(array $require): void
    {
        $this->require = $require;
    }

    public function getRequireDev(): array
    {
        return $this->require_dev;
    }

    public function setRequireDev(array $require_dev): void
    {
        $this->require_dev = $require_dev;
    }

    public function getAutoload(): array
    {
        return $this->autoload;
    }

    public function setAutoload(): void
    {
        $namespace = Str::finish($this->getNamespace() . '\\');

        $this->autoload = ['psr-4' => [$namespace => $this->autoload_path]];
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

    public function setExtra(string $key, $value): void
    {
        $this->extra[$key] = $value;
    }

    public function setRepositoryUrl(string $url): void
    {
        Http::validateUrl($url);

        $this->support['issues'] = Str::finish($url, '/issues');
        $this->support['source'] = $url;
    }

    public function getNamespace(): string
    {
        $studly = Str::studly($this->getName());

        return str_replace('/', '\\', $studly);
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
        $this->fill();

        return $this->items();
    }

    protected function fill(): void
    {
        $this->setAutoload();
    }

    protected function items(): array
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
}

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

    protected string $name;

    protected string $description;

    protected string $type = 'library';

    protected string $license;

    protected array $keywords = [];

    protected array $authors = [];

    protected array $support = [];

    protected array $require = [];

    protected array $require_dev = [];

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

        $this->setAuthors([$name, $email]);
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
        $this->require = $dependencies;
    }

    public function getRequireDev(): array
    {
        return $this->require_dev;
    }

    public function setRequireDev(array $dev_dependencies): void
    {
        $this->require_dev = $dev_dependencies;
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

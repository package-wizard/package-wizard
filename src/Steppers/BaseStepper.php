<?php

namespace Helldar\PackageWizard\Steppers;

use Helldar\PackageWizard\Concerns\Logger;
use Helldar\PackageWizard\Constants\Steps;
use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Services\Namespacing;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Arr;
use Helldar\Support\Facades\Helpers\Http;
use Helldar\Support\Facades\Helpers\Str;

abstract class BaseStepper implements Stepperable
{
    use Logger;
    use Makeable;

    protected $name;

    protected $description;

    protected $type = 'library';

    protected $license;

    protected $keywords = [];

    protected $authors = [];

    protected $support = [];

    protected $require = [
        'php' => '^8.0',
    ];

    protected $require_dev = [
        'mockery/mockery' => '^1.0',
        'phpunit/phpunit' => '^9.0',
    ];

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
        $this->log('Fill authors...');

        $this->authors      = array_map(function ($author) {
            [$name, $email] = $author;

            $this->log($name, $email, 'author handling');

            return compact('name', 'email');
        }, $authors);
    }

    public function pushAuthor(array $author): void
    {
        $name  = $author['name']  ?? $author[0]  ?? 'Example';
        $email = $author['email'] ?? $author[1] ?? 'mail@example.com';

        $this->log('Add author:', $name, $email);

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
        $this->log('Fill the list of basic dependencies');

        $this->filterDependencies($dependencies, $this->require, $this->require_dev, ['mockery/', 'phpunit/', 'composer/', 'orchestra/', 'symfony/thanks']);
    }

    public function getRequireDev(): array
    {
        return $this->require_dev;
    }

    public function setRequireDev(array $dependencies): void
    {
        $this->log('Fill the list of dev dependencies');

        $this->filterDependencies($dependencies, $this->require_dev, $this->require, ['php', 'composer-plugin-api']);
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
        $this->log('Setting a link to the repository:', $url);

        $url = Http::validatedUrl(trim($url, " \t\n\r\0\x0B/"));

        $this->support['issues'] = Str::finish($url, '/issues');
        $this->support['source'] = $url;

        $this->log('The values are set:');
        $this->log('ISSUES:', $this->support['issues']);
        $this->log('SOURCE:', $this->support['source']);
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
        $this->log('Getting the stepper values');

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
        $only = Arr::only($dependencies, static function ($key) use ($except_packages) {
            return ! in_array($key, $except_packages) && ! Str::startsWith($key, $except_packages);
        });

        $except = Arr::only($dependencies, static function ($key) use ($except_packages) {
            return in_array($key, $except_packages) || Str::startsWith($key, $except_packages);
        });

        $target   = array_merge($target, $only);
        $fallback = array_merge($fallback, $except);
    }
}

<?php

namespace Helldar\PackageWizard\Services;

use Helldar\PackageWizard\Contracts\Arrayable;
use Helldar\PackageWizard\Exceptions\UnknownMethodException;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Str;

/**
 * @method Structure name(string $package_name)
 * @method Structure description(string $description)
 * @method Structure type(string $type)
 * @method Structure license(string $license)
 * @method Structure keywords(array $keywords)
 */
final class Structure implements Arrayable
{
    use Makeable;

    protected $methods = [
        'name',
        'description',
        'type',
        'license',
        'keywords',
    ];

    protected $doc = [
        'type' => 'library',

        'config' => [
            'preferred-install' => 'dist',
            'sort-packages'     => true,
        ],

        'minimum-stability' => 'stable',
        'prefer-stable'     => true,
    ];

    public function authors(array $authors): self
    {
        $this->doc['authors'] = $authors;

        return $this;
    }

    public function repositoryUrl(string $url): self
    {
        $this->doc['support']['issues'] = $url . '/issues';
        $this->doc['support']['issues'] = $url;

        return $this;
    }

    public function requireDependencies(array $dependencies): self
    {
        if (! empty($dependencies)) {
            $this->doc['require'] = array_merge($this->doc['require'], $dependencies);
        }

        return $this;
    }

    public function requireDevDependencies(array $dependencies): self
    {
        if (! empty($dependencies)) {
            $this->doc['require-dev'] = array_merge($this->doc['require-dev'], $dependencies);
        }

        return $this;
    }

    public function packageNamespace(string $namespace): self
    {
        $namespace = str_replace('//', '\\\\', $namespace);

        $namespace = Str::finish($namespace, '\\\\');

        $this->doc['autoload']['psr-4'][$namespace] = 'src';

        return $this;
    }

    public function useTests(bool $use = true): self
    {
        if ($use) {
            $this->doc['autoload-dev']['psr-4']['Tests\\'] = 'tests';
        }

        return $this;
    }

    public function minimumStability(bool $stable = true): self
    {
        $this->doc['minimum-stability'] = $stable ? 'stable' : 'dev';

        return $this;
    }

    public function hasTests(): bool
    {
        return isset($this->doc['autoload-dev']['psr-4']['Tests\\']);
    }

    public function getName(): string
    {
        return $this->doc['name'];
    }

    public function getDescription(): string
    {
        return $this->doc['description'];
    }

    public function getAuthors(): array
    {
        return $this->doc['authors'];
    }

    public function getLicense(): ?string
    {
        return $this->doc['license'] ?? null;
    }

    public function magicMethod(string $method, string $value): self
    {
        $this->doc[$method] = $value;

        return $this;
    }

    public function __call($method, $arguments): Structure
    {
        if (in_array($method, $this->methods, true)) {
            return $this->magicMethod($method, ...$arguments);
        }

        throw new UnknownMethodException($method);
    }

    public function toArray(): array
    {
        return $this->doc;
    }
}

<?php

namespace Helldar\PackageWizard\Services;

use Helldar\PackageWizard\Contracts\Arrayable;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Str;

final class Structure implements Arrayable
{
    use Makeable;

    protected $doc = [
        'type' => 'library',

        'config' => [
            'preferred-install' => 'dist',
            'sort-packages'     => true,
        ],

        'minimum-stability' => 'stable',
        'prefer-stable'     => true,
    ];

    public function name(string $package_name): self
    {
        $this->doc['name'] = $package_name;

        return $this;
    }

    public function getName(): self
    {
        return $this->doc['name'];
    }

    public function description(string $description): self
    {
        $this->doc['description'] = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->doc['description'];
    }

    public function type(string $type): self
    {
        $this->doc['type'] = $type;

        return $this;
    }

    public function license(string $license): self
    {
        $this->doc['license'] = $license;

        return $this;
    }

    public function getLicense(): ?string
    {
        return $this->doc['license'] ?? null;
    }

    public function keywords(array $keywords): self
    {
        $this->doc['keywords'] = array_values(array_filter(array_unique($keywords)));

        return $this;
    }

    public function authors(array $authors): self
    {
        $this->doc['authors'] = $authors;

        return $this;
    }

    public function getAuthors(): array
    {
        return $this->doc['authors'];
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

    public function magicMethod(string $method, $value = null): self
    {
        $this->doc[$method] = $value;

        return $this;
    }

    public function toArray(): array
    {
        return $this->doc;
    }
}

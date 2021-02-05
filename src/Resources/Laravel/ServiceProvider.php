<?php

namespace Helldar\PackageWizard\Resources\Laravel;

use Helldar\PackageWizard\Resources\BaseResource;

final class ServiceProvider extends BaseResource
{
    public function setNamespace(string $namespace): self
    {
        $this->replaces['namespace'] = trim($namespace);

        return $this;
    }

    public function setName(string $name): self
    {
        $this->replaces['name'] = trim($name);

        return $this;
    }

    public function toString(): string
    {
        return $this->parser
            ->template($this->load())
            ->replacesMany($this->replaces)
            ->get();
    }

    protected function path(): string
    {
        return realpath(__DIR__ . '/../../../resources/types/laravel/ServiceProvider.stub');
    }
}

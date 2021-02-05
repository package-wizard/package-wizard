<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\PackageWizard\Services\Namespacing;

final class Custom extends BaseResource
{
    protected string $path;

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function toString(): string
    {
        return $this->getParser()
            ->replace('namespace', $this->getNamespace())
            ->get();
    }

    protected function getNamespace(): string
    {
        return rtrim($this->stepper->getNamespace(), Namespacing::SEPARATOR);
    }

    protected function getFullName(): string
    {
        return $this->stepper->getName();
    }

    protected function path(): string
    {
        return $this->path;
    }
}

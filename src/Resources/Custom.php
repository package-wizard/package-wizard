<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\PackageWizard\Services\Namespacing;
use Helldar\Support\Facades\Helpers\Arr;

final class Custom extends BaseResource
{
    /** @var string */
    protected $path;

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function toString(): string
    {
        return $this->getParser()
            ->replace('namespace', $this->getNamespace())
            ->replace('php', $this->getPhpVersions())
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

    protected function getPhpVersions(): string
    {
        $versions = Arr::get($this->stepper->getRequire(), 'php');

        if (empty($versions) || $versions === '*') {
            return '"8.0"';
        }

        $prepared = str_replace(['^', '>', '<', '>=', '<=', '~'], '', $versions);

        return implode('", "', explode('|', $prepared));
    }

    protected function path(): string
    {
        return $this->path;
    }
}

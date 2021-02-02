<?php

namespace Helldar\PackageWizard\Resources;

final class Readme extends BaseResource
{
    protected array $replaces = [];

    public function replaces(array $replaces): self
    {
        $this->replaces = $replaces;

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
        return realpath(__DIR__ . '/../../resources/README.md');
    }
}

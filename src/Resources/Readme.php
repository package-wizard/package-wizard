<?php

namespace Helldar\PackageWizard\Resources;

final class Readme extends BaseResource
{
    public function toString(): string
    {
        return $this->getParser()
            ->replace('description', $this->getDescription())
            ->get();
    }

    protected function getDescription(): string
    {
        return $this->stepper->getDescription();
    }

    protected function path(): string
    {
        return realpath(__DIR__ . '/../../resources/README.md.stub');
    }
}

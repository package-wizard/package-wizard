<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\Support\Facades\Helpers\Str;

final class Readme extends BaseResource
{
    public function toString(): string
    {
        return $this->getParser()
            ->replace('title', $this->getTitle())
            ->replace('description', $this->getDescription())
            ->get();
    }

    protected function getTitle(): string
    {
        $title = Str::after($this->getFullName(), '/');
        $title = Str::snake(Str::camel($title));
        $title = Str::title(str_replace('_', ' ', $title));

        return $title;
    }

    protected function getDescription(): string
    {
        return $this->stepper->getDescription();
    }

    protected function path(): string
    {
        return realpath(__DIR__ . '/../../resources/README.md');
    }
}

<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Contracts\Stringable;
use Helldar\PackageWizard\Services\Parser;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Str;

abstract class BaseResource implements Stringable
{
    use Makeable;

    protected Stepperable $stepper;

    /** @var \Helldar\PackageWizard\Services\Parser */
    protected Parser $parser;

    abstract protected function path(): string;

    public function stepper(Stepperable $stepper): self
    {
        $this->stepper = $stepper;

        return $this;
    }

    public function parser(Parser $parser): self
    {
        $this->parser = $parser;

        return $this;
    }

    public function getParser(): Parser
    {
        return $this->parser
            ->template($this->path())
            ->replace('fullname', $this->getFullName())
            ->replace('name', $this->getShortName());
    }

    protected function getFullName(): string
    {
        return $this->stepper->getName();
    }

    protected function getShortName(): string
    {
        return Str::after($this->getFullName(), '/');
    }
}

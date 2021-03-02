<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\PackageWizard\Concerns\Logger;
use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Contracts\Stringable;
use Helldar\PackageWizard\Services\Parser;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Str;

abstract class BaseResource implements Stringable
{
    use Logger;
    use Makeable;

    /** @var \Helldar\PackageWizard\Contracts\Stepperable */
    protected $stepper;

    /** @var \Helldar\PackageWizard\Services\Parser */
    protected $parser;

    abstract protected function path(): string;

    public function stepper(Stepperable $stepper): self
    {
        $this->log('Set the stepper:', $stepper);

        $this->stepper = $stepper;

        return $this;
    }

    public function parser(Parser $parser): self
    {
        $this->log('Set the parser:', $parser);

        $this->parser = $parser;

        return $this;
    }

    public function getParser(): Parser
    {
        $this->log('Getting a parsed content from path:', $this->path());

        return $this->parser
            ->template($this->path())
            ->replace('title', $this->getTitle())
            ->replace('fullname', $this->getFullName())
            ->replace('name', $this->getShortName());
    }

    protected function getTitle(): string
    {
        $title = Str::after($this->getFullName(), '/');
        $title = Str::snake(Str::camel($title));
        $title = Str::title(str_replace('_', ' ', $title));

        return $title;
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

<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\PackageWizard\Contracts\Stringable;
use Helldar\PackageWizard\Services\Parser;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Filesystem\File;

abstract class BaseResource implements Stringable
{
    use Makeable;

    /** @var \Helldar\PackageWizard\Services\Parser */
    protected Parser $parser;

    protected array $replaces = [];

    abstract protected function path(): string;

    public function replaces(array $replaces): self
    {
        $this->replaces = $replaces;

        return $this;
    }

    public function parser(Parser $parser): self
    {
        $this->parser = $parser;

        return $this;
    }

    protected function load(): string
    {
        return File::exists($this->path()) ? file_get_contents($this->path()) : '';
    }
}

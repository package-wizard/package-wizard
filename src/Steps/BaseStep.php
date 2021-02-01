<?php

namespace Helldar\PackageWizard\Steps;

use Helldar\PackageWizard\Contracts\Stepable;
use Helldar\Support\Concerns\Makeable;

abstract class BaseStep implements Stepable
{
    use Makeable;

    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function get()
    {
        if ($value = $this->process()) {
            return $value;
        }

        return $this->get();
    }

    abstract protected function process();
}

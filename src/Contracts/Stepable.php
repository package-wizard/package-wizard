<?php

namespace Helldar\PackageWizard\Contracts;

interface Stepable
{
    public function question(string $question): self;

    public function get();
}

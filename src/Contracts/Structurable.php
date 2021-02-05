<?php

namespace Helldar\PackageWizard\Contracts;

interface Structurable
{
    public function __construct(Stepperable $stepper);

    public function path(string $path): self;

    public function handle(): void;
}

<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Contracts;

interface DependencyManager
{
    public function add(string $directory, iterable $packages, bool $dev = false): void;

    public function install(string $directory): void;

    public function remove(string $directory, iterable $packages, bool $dev = false): void;
}

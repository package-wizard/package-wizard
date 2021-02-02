<?php

namespace Helldar\PackageWizard\Steppers;

use Composer\IO\IOInterface;
use Helldar\PackageWizard\Concerns\IO;
use Helldar\Support\Concerns\Makeable;

final class Manager
{
    use IO;
    use Makeable;

    protected string $question = 'Type of package';

    protected string $default_stepper = Native::class;

    protected string $default_type = 'library';

    protected array $map = [
        'composer-plugin' => ComposerPlugin::class,
        'laravel-package' => LaravelPackage::class,
        'symfony-bundle'  => SymfonyBundle::class,

        'library'     => Native::class,
        'metapackage' => Native::class,
        'project'     => Native::class,
    ];

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function get(): string
    {
        $type = $this->ask();

        return $this->map[$type] ?? $this->default_stepper;
    }

    protected function ask()
    {
        return $this->getIO()->select($this->question, $this->choices(), $this->default_type);
    }

    protected function choices(): array
    {
        return array_keys($this->map);
    }
}

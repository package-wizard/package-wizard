<?php

namespace Helldar\PackageWizard\Steppers;

use Composer\IO\IOInterface;
use Helldar\PackageWizard\Concerns\IO;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Arr;

final class Manager
{
    use IO;
    use Makeable;

    protected $question = 'Type of package';

    protected $default_stepper = Native::class;

    protected $default_type = 'library';

    protected $map = [
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
        $code = $this->code($this->ask());

        return Arr::get($this->map, $code, $this->default_stepper);
    }

    protected function ask()
    {
        return $this->getIO()->select($this->question, $this->choices(), $this->default_type);
    }

    protected function choices(): array
    {
        return array_keys($this->map);
    }

    protected function code(int $key): ?string
    {
        return Arr::get($this->choices(), $key);
    }
}

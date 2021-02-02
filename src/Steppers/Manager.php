<?php

namespace Helldar\PackageWizard\Steppers;

use Composer\IO\IOInterface;
use Helldar\PackageWizard\Services\Output;
use Helldar\PackageWizard\Steps\Choice;
use Helldar\Support\Concerns\Makeable;

final class Manager
{
    use Makeable;

    /** @var \Composer\IO\IOInterface */
    protected $io;

    /** @var \Helldar\PackageWizard\Services\Output */
    protected $output;

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

    public function __construct(IOInterface $io, Output $output)
    {
        $this->io     = $io;
        $this->output = $output;
    }

    public function get(): string
    {
        $type = $this->ask();

        return $this->map[$type] ?? $this->default_stepper;
    }

    protected function ask()
    {
        return Choice::make($this->io, $this->output)
            ->question($this->question)
            ->choices($this->choices())
            ->back($this->default_type)
            ->get();
    }

    protected function choices(): array
    {
        return array_keys($this->map);
    }
}

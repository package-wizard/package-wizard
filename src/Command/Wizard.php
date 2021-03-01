<?php

namespace Helldar\PackageWizard\Command;

use Composer\InstalledVersions;
use Exception;
use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Facades\Storage;
use Helldar\PackageWizard\Steppers\Manager;
use Symfony\Component\Console\Input\ArrayInput;

final class Wizard extends BaseCommand
{
    public function handle()
    {
        $this->welcome();

        $stepper = $this->resolveStepper();

        $this->fill($stepper);
        $this->store($stepper);
        $this->install();
    }

    protected function configure()
    {
        $this
            ->setName('package:init')
            ->setDescription('Creates a basic package presets in current directory.');
    }

    protected function fill(Stepperable $stepper): void
    {
        foreach ($stepper->steps() as $method) {
            try {
                $value = $this->ask($method);

                if (! empty($value) && ! is_bool($value) && ! is_numeric($value)) {
                    call_user_func([$stepper, $method], $value);
                }
            } catch (Exception $e) {
                $this->throwError($e, $method);
            }
        }
    }

    protected function store(Stepperable $stepper): void
    {
        Storage::stepper($stepper)->store();
    }

    protected function install(): void
    {
        $question = 'Would you like to install dependencies now [<comment>yes</comment>]? ';

        if ($this->getIO()->askConfirmation($question)) {
            $this->installDependencies();
        }
    }

    protected function installDependencies(): void
    {
        try {
            $install = $this->getApplication()->find('install');

            $install->run(new ArrayInput([]), $this->output);
        } catch (Exception $e) {
            $this->error('Could not install dependencies. Run `composer install` to see more information.');
        }
    }

    protected function welcome(): void
    {
        $this->infoBlock('Welcome to the package generator', true, $this->version());
        $this->lineBlock('This command will guide you through creating your package.', true);
    }

    protected function stepper(): string
    {
        return Manager::make($this->getIO())->get();
    }

    protected function resolveStepper(): Stepperable
    {
        /** @var \Helldar\PackageWizard\Steppers\BaseStepper $stepper */
        $stepper = $this->stepper();

        return $stepper::make();
    }

    protected function version(): ?string
    {
        return InstalledVersions::getPrettyVersion('andrey-helldar/package-wizard');
    }
}

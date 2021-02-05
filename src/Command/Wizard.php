<?php

namespace Helldar\PackageWizard\Command;

use Exception;
use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Contracts\Structurable;
use Helldar\PackageWizard\Services\Storage;
use Helldar\PackageWizard\Steppers\Manager;
use Symfony\Component\Console\Input\ArrayInput;

final class Wizard extends BaseCommand
{
    public function handle()
    {
        $this->welcome();

        $stepper = $this->resolveStepper();

        $this->fill($stepper);
        $this->structure($stepper);
        $this->store($stepper);
        $this->install();
    }

    protected function configure()
    {
        $this
            ->setName('package:init')
            ->setDescription('Creates a basic package presets in current directory.');
    }

    protected function stepper(): string
    {
        return Manager::make($this->getIO())->get();
    }

    protected function fill(Stepperable $stepper): void
    {
        foreach ($stepper->steps() as $method) {
            try {
                if ($value = $this->ask($method)) {
                    call_user_func([$stepper, $method], $value);
                }
            } catch (Exception $e) {
                $this->throwError($e, $method);
            }
        }
    }

    protected function structure(Stepperable $stepper): void
    {
        $structure = $this->resolveStructure($stepper);
    }

    protected function store(Stepperable $stepper): void
    {
        Storage::make()->stepper($stepper)->store();
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
        $this->infoBlock('Welcome to the package generator', true);
        $this->lineBlock('This command will guide you through creating your package.', true);
    }

    protected function resolveStepper(): Stepperable
    {
        /** @var \Helldar\PackageWizard\Steppers\BaseStepper $stepper */
        $stepper = $this->stepper();

        return $stepper::make();
    }

    protected function resolveStructure(Stepperable $stepper): Structurable
    {
        /** @var \Helldar\PackageWizard\Structures\BaseStructure $structure */
        $structure = $stepper->getStructure();

        return $structure::make($stepper);
    }
}

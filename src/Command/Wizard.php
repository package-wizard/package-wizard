<?php

namespace Helldar\PackageWizard\Command;

use Exception;
use Helldar\PackageWizard\Contracts\Stepperable;
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
        $this->store($stepper);
//        $this->install();
    }

    protected function configure()
    {
        $this
            ->setName('package:init')
            ->setDescription('Creates a basic package presets in current directory.');
    }

    protected function resolveStepper(): Stepperable
    {
        /** @var \Helldar\PackageWizard\Steppers\BaseStepper $stepper */
        $stepper = $this->stepper();

        return $stepper::make();
    }

    protected function stepper(): string
    {
        return Manager::make($this->getIO())->get();
    }

    protected function fill(Stepperable $stepper): void
    {
        foreach ($stepper->steps() as $step) {
            try {
                $value = $this->ask($step);

                if (! empty($value) || is_bool($value) || is_numeric($value)) {
                    call_user_func([$stepper, $step], $value);
                }
            }
            catch (Exception $e) {
                $this->throwError($e, $step);
            }
        }
    }

    protected function store(Stepperable $stepper): void
    {
        Storage::make()->stepper($stepper)->store();
    }

    protected function install(): void
    {
        try {
            $install = $this->getApplication()->find('install');

            $install->run(new ArrayInput([]), $this->output);
        }
        catch (Exception $e) {
            $this->error('Could not install dependencies. Run `composer install` to see more information.');
        }
    }

    protected function welcome(): void
    {
        $this->infoBlock('Welcome to the package generator', true);

        $this->lineBlock('This command will guide you through creating your package.', true);
    }
}

<?php

namespace Helldar\PackageWizard\Command;

use Helldar\PackageWizard\Contracts\Stepperable;
use Helldar\PackageWizard\Services\Storage;
use Helldar\PackageWizard\Steppers\Manager;

final class Wizard extends BaseCommand
{
    public function handle()
    {
        $stepper = $this->resolveStepper();

//        $this->fill($stepper);
        $this->store($stepper);
        $this->install();
    }

    protected function configure()
    {
        $this
            ->setName('package:init')
            ->setDescription('Helps to initialize a new package project');
    }

    protected function resolveStepper(): Stepperable
    {
        /** @var \Helldar\PackageWizard\Steppers\BaseStepper $stepper */
        $stepper = $this->stepper();

        return $stepper::make();
    }

    protected function stepper(): string
    {
        return Manager::make($this->getIO(), $this->output())->get();
    }

    protected function fill(Stepperable $stepper): void
    {
        foreach ($stepper->steps() as $step) {
            $value = $this->ask($step);

            if (! empty($value) || is_bool($value) || is_numeric($value)) {
                call_user_func([$stepper, $step], $value);
            }
        }
    }

    protected function store(Stepperable $stepper): void
    {
        Storage::make()
            ->basePath($this->basePath() . '/build')
            ->stepper($stepper)
            ->store();
    }

    protected function install(): void
    {
        // composer update
    }
}

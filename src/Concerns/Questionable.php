<?php

namespace Helldar\PackageWizard\Concerns;

use Composer\Factory;
use Composer\Repository\RepositoryFactory;
use Helldar\PackageWizard\Constants\Licenses;
use Helldar\PackageWizard\Constants\Steps;
use Helldar\PackageWizard\Exceptions\UnknownStepException;
use Helldar\Support\Facades\Helpers\Str;

/** @mixin \Helldar\PackageWizard\Command\BaseCommand */
trait Questionable
{
    protected function ask(string $step)
    {
        switch ($step) {
            case Steps::NAME:
                return $this->askName();

            case Steps::DESCRIPTION:
                return $this->askDescription();

            case Steps::LICENSE:
                return $this->askLicense();

            case Steps::KEYWORDS:
                return $this->askKeywords();

            case Steps::AUTHORS:
                return $this->askAuthors();

            case Steps::REPOSITORY_URL:
                return $this->askRepositoryUrl();

            case Steps::REQUIRE:
                return $this->askDependencies();

            case Steps::REQUIRE_DEV:
                return $this->askDevDependencies();

            default:
                throw new UnknownStepException($step);
        }
    }

    protected function askName(): string
    {
        return $this->inputText('Name of the package')->get();
    }

    protected function askDescription(): string
    {
        return $this->inputText('Description of package')->get();
    }

    protected function askLicense(): string
    {
        return $this->inputChoice('License of package', Licenses::available(), Licenses::DEFAULT_LICENSE)->get();
    }

    protected function askKeywords(): ?array
    {
        if ($this->askConfirm('Want to specify keywords')) {
            return $this->inputArray('Specify the application keywords')->get();
        }

        return null;
    }

    protected function askAuthors(): array
    {
        return $this->inputKeyValue('Authors names of package', ['name', 'email'])->get();
    }

    protected function askRepositoryUrl(): string
    {
        $url = $this->inputUrl('Repository URL of package')->get();

        $config = Factory::createConfig($this->getIO());

        return RepositoryFactory::configFromString($this->getIO(), $config, $url);
    }

    protected function askDependencies(): ?array
    {
        if ($this->askConfirm('Want to specify required packages')) {
            return $this
                ->inputArray('Package to require with a version constraint, e.g. foo/bar:1.0.0 or foo/bar=1.0.0 or "foo/bar 1.0.0"')
                ->get();
        }

        return null;
    }

    protected function askDevDependencies(): ?array
    {
        if ($this->askConfirm('Want to specify dev-required packages')) {
            return $this
                ->inputArray('Package to require for development with a version constraint, e.g. foo/bar:1.0.0 or foo/bar=1.0.0 or "foo/bar 1.0.0"')
                ->get();
        }

        return null;
    }

    protected function askConfirm(string $question, bool $default = true): bool
    {
        $question = Str::finish($question, ' (Y/n)?');

        return $this->getIO()->askConfirmation($question, $default);
    }
}

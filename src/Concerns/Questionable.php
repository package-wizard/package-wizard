<?php

namespace Helldar\PackageWizard\Concerns;

use Helldar\PackageWizard\Constants\Steps;
use Helldar\PackageWizard\Contracts\Stepable;
use Helldar\PackageWizard\Exceptions\UnknownStepException;
use Helldar\PackageWizard\Steps\Author;
use Helldar\PackageWizard\Steps\Dependencies;
use Helldar\PackageWizard\Steps\Description;
use Helldar\PackageWizard\Steps\DevDependencies;
use Helldar\PackageWizard\Steps\Keywords;
use Helldar\PackageWizard\Steps\License;
use Helldar\PackageWizard\Steps\Name;
use Helldar\PackageWizard\Steps\RepositoryUrl;
use Helldar\Support\Facades\Helpers\Instance;

/**
 * @mixin \Helldar\PackageWizard\Command\BaseCommand
 * @mixin \Helldar\PackageWizard\Concerns\Git
 */
trait Questionable
{
    protected function ask(string $step)
    {
        $questions = $this->questions();

        if (isset($questions[$step])) {
            return $this->resolveStep($questions[$step])->get();
        }

        throw new UnknownStepException($step);
    }

    protected function questions(): array
    {
        return [
            Steps::NAME           => Name::class,
            Steps::DESCRIPTION    => Description::class,
            Steps::LICENSE        => License::class,
            Steps::KEYWORDS       => Keywords::class,
            Steps::AUTHORS        => Author::class,
            Steps::REPOSITORY_URL => RepositoryUrl::class,
            Steps::REQUIRE        => Dependencies::class,
            Steps::REQUIRE_DEV    => DevDependencies::class,
        ];
    }

    /**
     * @param  \Helldar\PackageWizard\Steps\BaseStep|string  $step
     *
     * @throws \Helldar\PackageWizard\Exceptions\UnknownStepException
     *
     * @return \Helldar\PackageWizard\Contracts\Stepable
     */
    protected function resolveStep(string $step): Stepable
    {
        if (Instance::of($step, Stepable::class)) {
            return $step::make($this->getIO(), $this->input, $this->output, $this->getGitConfig());
        }

        throw new UnknownStepException($step);
    }
}

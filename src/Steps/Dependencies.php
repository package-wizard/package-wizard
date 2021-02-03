<?php

namespace Helldar\PackageWizard\Steps;

use Composer\Package\Version\VersionParser;

class Dependencies extends BaseStep
{
    protected string $question = 'Would you like to define your dependencies (require) [<comment>Y/n</comment>]?';

    protected string $sub_question = 'Search for a package';

    protected bool $ask_many = true;

    protected function input(): ?string
    {
        return $this->getIO()->askAndValidate($this->question($this->sub_question), function ($value) {
            if (preg_match('{^\s*(?P<name>[\S/]+)(?:\s+(?P<version>\S+))?\s*$}', $value, $package_matches)) {
                if (isset($package_matches['version'])) {
                    $this->versionParser()->parseConstraints($package_matches['version']);

                    return $package_matches['name'] . ' ' . $package_matches['version'];
                }

                return $package_matches['name'] . ' ^1.0';
            }

            return null;
        });
    }

    protected function skip(): bool
    {
        return ! $this->getIO()->askConfirmation($this->question());
    }

    protected function versionParser(): VersionParser
    {
        return new VersionParser();
    }
}

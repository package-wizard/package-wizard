<?php

namespace Helldar\PackageWizard\Steps;

final class DevDependencies extends Dependencies
{
    protected string $question = 'Would you like to define your dependencies (require-dev) [<comment>yes</comment>]?';
}

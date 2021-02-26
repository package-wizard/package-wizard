<?php

namespace Helldar\PackageWizard\Steps;

final class DevDependencies extends Dependencies
{
    protected $question = 'Would you like to define your dependencies (require-dev) [<comment>Y/n</comment>]?';
}

<?php

namespace Helldar\PackageWizard\Steps;

use Composer\IO\IOInterface;
use Helldar\PackageWizard\Command\InitCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Dependencies extends BaseStep
{
    protected string $question = 'Would you like to define your dependencies (require) [<comment>yes</comment>]?';

    protected string $question_package = 'Search for a package: ';

    protected InitCommand $init_command;

    public function __construct(IOInterface $io, InputInterface $input, OutputInterface $output, array $git = [])
    {
        parent::__construct($io, $input, $output, $git);

        $this->init_command = new InitCommand();
    }

    protected function input()
    {
        return $this->init_command->getDetermineRequirements($this->input, $this->output);
    }
}

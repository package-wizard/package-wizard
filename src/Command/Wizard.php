<?php

namespace Helldar\PackageWizard\Command;

use Helldar\PackageWizard\Constants\Steps;
use Helldar\PackageWizard\Services\Storage;
use Helldar\PackageWizard\Services\Structure;
use Helldar\PackageWizard\Steps\Choice;

final class Wizard extends BaseCommand
{
    /** @var \Helldar\PackageWizard\Services\Structure */
    protected $structure;

    public function handle()
    {
        $this->structure = Structure::make();

        $this->fill();
        $this->store();
        $this->install();
    }

    protected function configure()
    {
        $this
            ->setName('package:init')
            ->setDescription('Helps to initialize a new package project');
    }

    protected function fill(): void
    {
        foreach ($this->steps() as $step => $question) {
            $answer = $question->get();

            if ($step === Steps::USE_TESTS && $answer === true) {
                $this->pushTestsDependencies();
            }

            $this->pushStructure($step, $answer);
        }
    }

    protected function store(): void
    {
        Storage::make()
            ->basePath($this->basePath() . '/build')
            ->structure($this->structure)
            ->store();
    }

    protected function pushTestsDependencies(): void
    {
        $framework = Choice::make('What\s a framework?', ['Native', 'Laravel']);

        $dependencies = [
            ['mockery/mockery' => '^1.3.1'],
            ['phpunit/phpunit' => '^9.0'],
        ];

        if ($framework === 'Laravel') {
            $dependencies = array_merge($dependencies, [['orchestra/testbench' => '^6.0']]);
        }

        $this->structure->requireDevDependencies($dependencies);
    }

    protected function pushStructure(string $method, $value): void
    {
        $this->structure->{$method}($value);
    }

    /**
     * @return array|\Helldar\PackageWizard\Contracts\Stepable[]
     */
    protected function steps(): array
    {
        return [
            Steps::NAME        => $this->inputText('What\'s a package name?'),
            Steps::DESCRIPTION => $this->inputText('What\'s a package description?'),

            Steps::TYPE => $this->inputChoice('What\s a composer type', ['library', 'metapackage', 'composer-plugin', 'project', 'symfony-bundle'], 'library'),

            Steps::LICENSE => $this->inputText('What\'s a license?'),

            Steps::KEYWORDS => $this->inputArray('What\s a keywords?'),

            Steps::AUTHORS => $this->inputKeyValue('What\s a authors?', ['name', 'email']),

            Steps::REPOSITORY_URL => $this->inputUrl('What\s a repository URL?'),

            Steps::REQUIRE     => $this->inputKeyValue('What\s a require dependencies?', ['package', 'versions']),
            Steps::REQUIRE_DEV => $this->inputKeyValue('What\s a require dev dependencies?', ['package', 'versions']),

            Steps::PACKAGE_NAMESPACE => $this->inputText('What\s a namespace?'),

            Steps::USE_TESTS => $this->inputBoolean('You\'re using tests?'),
            Steps::STABILITY => $this->inputBoolean('Minimum stability - stable?'),
        ];
    }

    protected function install(): void
    {
        // composer update
    }
}

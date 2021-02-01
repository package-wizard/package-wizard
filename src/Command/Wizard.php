<?php

namespace Helldar\PackageWizard\Command;

use Helldar\PackageWizard\Constants\Steps;
use Helldar\PackageWizard\Services\Storage;
use Helldar\PackageWizard\Services\Structure;
use Helldar\PackageWizard\Steps\Arr;
use Helldar\PackageWizard\Steps\Boolean;
use Helldar\PackageWizard\Steps\Choice;
use Helldar\PackageWizard\Steps\KeyValue;
use Helldar\PackageWizard\Steps\Text;
use Helldar\PackageWizard\Steps\Url;

final class Wizard
{
    /** @var \Helldar\PackageWizard\Services\Structure */
    protected $structure;

    public function handle(Structure $structure)
    {
        $this->structure = $structure;

        $this->fill();
        $this->store();
        $this->install();
    }

    protected function fill(): void
    {
        foreach ($this->steps() as $step => $value) {
            if ($step === Steps::USE_TESTS && $value === true) {
                $this->pushTestsDependencies();
            }

            $this->pushStructure($step, $value);
        }
    }

    protected function store(): void
    {
        Storage::make()
            ->basePath(__DIR__ . '/build')
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

    protected function steps(): array
    {
        return [
            Steps::NAME        => Text::make('What\'s a package name?'),
            Steps::DESCRIPTION => Text::make('What\'s a package description?'),

            Steps::TYPE => Choice::make('What\s a composer type', ['library', 'metapackage', 'composer-plugin', 'project', 'symfony-bundle']),

            Steps::LICENSE => Text::make('What\'s a license?'),

            Steps::KEYWORDS => Arr::make('What\s a keywords?'),

            Steps::AUTHORS => KeyValue::make('What\s a authors?'),

            Steps::REPOSITORY_URL => Url::make('What\s a repository URL?'),

            Steps::REQUIRE     => KeyValue::make('What\s a require dependencies?'),
            Steps::REQUIRE_DEV => KeyValue::make('What\s a require dev dependencies?'),

            Steps::PACKAGE_NAMESPACE => Text::make('What\s a namespace?'),

            Steps::USE_TESTS => Boolean::make('You\'re using tests?'),
            Steps::STABILITY => Boolean::make('Minimum stability - stable?'),
        ];
    }

    protected function install(): void
    {
        // composer update
    }
}

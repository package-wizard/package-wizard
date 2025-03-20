<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use DragonCode\Support\Facades\Filesystem\Directory;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use PackageWizard\Installer\Data\GitHub\LicenseData;
use PackageWizard\Installer\Integrations\GitHub;

use function file_put_contents;
use function Laravel\Prompts\spin;
use function PackageWizard\Installer\resource_path;

class LicensesCommand extends Command
{
    protected $signature = 'licenses';

    protected $description = 'Download licenses';

    public function handle(GitHub $github): void
    {
        spin(fn () => $this->cleanUp(), 'Clean up...');

        $licenses = spin(fn () => $github->licenses(), 'Getting licenses...');

        spin(fn () => $this->license($github, $licenses), 'Storing licenses...');
        spin(fn () => $this->list($licenses), 'Storing list...');
    }

    protected function cleanUp(): void
    {
        Directory::ensureDirectory($this->path(), can_delete: true);
    }

    protected function license(GitHub $github, Collection $licenses): void
    {
        $licenses->each(function (LicenseData $item) use ($github) {
            $license = $github->license($item->id);

            $this->store(
                $license->filename,
                $license->content
            );
        });
    }

    /**
     * @param  Collection<LicenseData>  $licenses
     */
    protected function list(Collection $licenses): void
    {
        $content = $licenses
            ->pluck('name', 'filename')
            ->sort()
            ->toJson(JSON_PRETTY_PRINT);

        $this->store('list.json', $content);
    }

    protected function store(string $filename, string $content): void
    {
        file_put_contents($this->path() . '/' . $filename, $content);
    }

    protected function path(): string
    {
        return resource_path('licenses');
    }
}

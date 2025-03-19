<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use PackageWizard\Installer\Data\CopyData;

class CopyFilesAction extends Action
{
    protected function title(): string
    {
        return __('info.copying_files');
    }

    protected function perform(): void
    {
        $this->config()->copies->each(
            fn (CopyData $item) => $this->copy($this->directory(), $item)
        );
    }

    protected function copy(string $directory, CopyData $item): void
    {
        $this->filesystem->copy(
            source: $directory . '/' . $item->source,
            target: $directory . '/' . $item->target
        );
    }
}

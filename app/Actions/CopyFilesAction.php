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
            fn (CopyData $item) => $this->copy($item)
        );
    }

    protected function copy(CopyData $item): void
    {
        $this->filesystem->copy(
            source: $this->sourcePath($item),
            target: $this->directory() . '/' . $item->target
        );
    }

    protected function sourcePath(CopyData $item): string
    {
        if ($item->absolute) {
            return $item->source;
        }

        return $this->directory() . '/' . $item->source;
    }
}

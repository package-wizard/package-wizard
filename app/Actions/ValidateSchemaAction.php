<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use PackageWizard\Installer\Exceptions\InvalidJsonException;
use PackageWizard\Installer\Services\SchemaValidatorService;
use stdClass;

class ValidateSchemaAction extends Action
{
    protected function title(): string
    {
        return __('info.validating_schema');
    }

    protected function perform(): void
    {
        if (! $this->exists()) {
            return;
        }

        if (! json_validate($this->content())) {
            throw new InvalidJsonException($this->path());
        }

        $this->validator()->validate(
            $this->object()
        );
    }

    protected function object(): stdClass
    {
        return json_decode($this->content(), false, 512, JSON_THROW_ON_ERROR);
    }

    protected function content(): string
    {
        return file_get_contents($this->path());
    }

    protected function exists(): bool
    {
        return file_exists($this->path());
    }

    protected function path(): string
    {
        return $this->directory() . '/' . $this->filename();
    }

    protected function filename(): string
    {
        return config('wizard.filename');
    }

    protected function validator(): SchemaValidatorService
    {
        return new SchemaValidatorService();
    }
}

<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use JsonSchema\Validator;
use PackageWizard\Installer\Exceptions\JsonSchemaException;
use stdClass;

use function PackageWizard\Installer\resource_path;
use function realpath;

class SchemaValidatorService
{
    public function __construct(
        protected Validator $schema = new Validator()
    ) {}

    public function validate(stdClass $data): void
    {
        $this->schema->validate($data, $this->reference());

        if (! $this->schema->isValid()) {
            $this->throw();
        }
    }

    /**
     * @throws JsonSchemaException
     */
    protected function throw(): void
    {
        throw new JsonSchemaException($this->schema->getErrors());
    }

    protected function reference(): stdClass
    {
        return (object) ['$ref' => 'file://' . $this->schema()];
    }

    protected function schema(): string
    {
        return realpath(resource_path('schemas/schema-v2.json'));
    }
}

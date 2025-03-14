<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use JsonSchema\Validator;
use PackageWizard\Installer\Exceptions\JsonSchemaException;
use stdClass;

use function realpath;

class SchemaValidatorService
{
    public function __construct(
        protected Validator $schema = new Validator()
    ) {}

    /**
     * @throws JsonSchemaException
     */
    public function validate(array $data): void
    {
        if ($this->schema->validate($data, $this->reference())) {
            return;
        }

        $this->throw();
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
        return realpath(__DIR__ . '/../../resources/schemas/schema-v2.json');
    }
}

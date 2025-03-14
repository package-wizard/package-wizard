<?php

declare(strict_types=1);

use PackageWizard\Installer\Exceptions\JsonSchemaException;
use PackageWizard\Installer\Services\SchemaValidatorService;

test('correct', function () {
    $validator = new SchemaValidatorService();

    $validator->validate(rule('correct-1'));

    expect(true)->toBeTrue();
});

test('incorrect', function (int $attempt) {
    $validator = new SchemaValidatorService();

    $validator->validate(rule('incorrect-' . $attempt));
})
    ->throws(JsonSchemaException::class, 'JSON does not validate. Violations:')
    ->repeat(4);

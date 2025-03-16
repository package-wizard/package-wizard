<?php

declare(strict_types=1);

use PackageWizard\Installer\Exceptions\JsonSchemaException;

test('correct', function () {
    validateSchema('correct-1');

    expect(true)->toBeTrue();
});

test('incorrect', function (int $attempt) {
    validateSchema('incorrect-' . $attempt);
})
    ->throws(JsonSchemaException::class, 'JSON does not validate')
    ->repeat(4);

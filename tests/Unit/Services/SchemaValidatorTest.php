<?php

declare(strict_types=1);

use PackageWizard\Installer\Data\SomeData;
use PackageWizard\Installer\Exceptions\JsonSchemaException;

test('correct', function () {
    $data = SomeData::from([
        'foo' => 'qwe',
    ]);

    dd(
        $data->foo
    );

    validateSchema('correct-1');

    expect(true)->toBeTrue();
});

test('incorrect', function (int $attempt) {
    validateSchema('incorrect-' . $attempt);
})
    ->throws(JsonSchemaException::class, 'JSON does not validate')
    ->repeat(4);

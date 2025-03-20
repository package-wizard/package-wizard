<?php

declare(strict_types=1);

use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Support\Str;

function excludePath(string $path): bool
{
    return Str::contains($path, [
        'vendor',
        'node_modules',

        'composer.lock',
        'package-lock.json',
        'yarn.lock',
        '.yarn',
        '.pnp',
    ]);
}

function assertFileSnapshot(string $filename): void
{
    expect(file_get_contents($filename))->toMatchSnapshot(
        "The \"$filename\" file does not match to the snapshot."
    );
}

function assertFileSnapshots(string $directory): void
{
    $files = File::names($directory, recursive: true);

    foreach ($files as $file) {
        if (! excludePath($file)) {
            assertFileSnapshot($directory . '/' . $file);
        }
    }

    $files = collect($files)
        ->map(static fn (string $name) => Str::replace('\\', '/', $name))
        ->reject(static fn (string $name) => excludePath($name))
        ->values()
        ->all();

    expect($files)->toMatchSnapshot(
        "The number of files in the \"$directory\" folder does not match the expected value."
    );
}

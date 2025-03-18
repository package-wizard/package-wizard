<?php

declare(strict_types=1);

use DragonCode\Support\Facades\Filesystem\File;
use DragonCode\Support\Facades\Filesystem\Path;
use Illuminate\Support\Str;

function assertFileSnapshot(string $filename): void
{
    if (Path::basename($filename) === 'composer.lock') {
        return;
    }

    expect(file_get_contents($filename))->toMatchSnapshot(
        "The \"$filename\" file does not match to the snapshot."
    );
}

function assertFileSnapshots(string $directory): void
{
    $files = File::names($directory, recursive: true);

    foreach ($files as $file) {
        assertFileSnapshot($directory . '/' . $file);
    }

    $files = collect($files)
        ->map(static fn (string $name) => Str::replace('\\', '/', $name))
        ->all();

    expect($files)->toMatchSnapshot(
        "The number of files in the \"$directory\" folder does not match the expected value."
    );
}

<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Command;

class NewCommand extends Command
{
    protected $signature = 'new';

    protected $description = 'Create new package';

    public function handle(): void {}
}

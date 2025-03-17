<?php

declare(strict_types=1);

arch()
    ->expect('App\Integrations')
    ->not->toHaveSuffix('Integration');

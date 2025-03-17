<?php

declare(strict_types=1);

arch()
    ->expect('App\Support')
    ->not->toHaveSuffix('Support');

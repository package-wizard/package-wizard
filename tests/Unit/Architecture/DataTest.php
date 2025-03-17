<?php

declare(strict_types=1);

arch()
    ->expect('App\Data')
    ->toHaveSuffix('Data')
    ->ignoring('App\Data\Casts');

arch()
    ->expect('App\Data\Casts')
    ->toHaveSuffix('Cast');

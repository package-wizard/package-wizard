<?php

declare(strict_types=1);

arch()
    ->expect('App\Enums')
    ->toBeEnums();

arch()
    ->expect('App\Enums')
    ->toHaveSuffix('Enum');

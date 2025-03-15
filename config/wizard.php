<?php

declare(strict_types=1);

use PackageWizard\Installer\Enums\TypeEnum;

return [
    'default' => [
        'wizard' => [
            'install' => true,
            'clean'   => true,
        ],

        'project'  => [],
        'authors'  => [],
        'replaces' => [],

        'variables' => [
            [
                'type'    => TypeEnum::Year->value,
                'replace' => [':year:'],
            ],
        ],

        'questions' => [
            [
                'type'    => TypeEnum::License->value,
                'default' => 'mit',
                'replace' => ':license:',
            ],
        ],
    ],
];

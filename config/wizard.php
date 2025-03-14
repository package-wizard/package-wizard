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
        'renames'  => [],
        'removes'  => [],

        'variables' => [
            [
                'type'    => TypeEnum::Year,
                'replace' => [':year:'],
            ],
        ],

        'questions' => [
            [
                'type'        => TypeEnum::Ask,
                'replace'     => ':app-name:',
                'question'    => 'Which name of your project?',
                'placeholder' => 'E.g. Laravel Deploy Operations',
            ],
            [
                'type'        => TypeEnum::Ask,
                'replace'     => 'dragon-code/laravel-deploy-operations',
                'question'    => 'Which name of your package?',
                'placeholder' => 'E.g. dragon-code/laravel-deploy-operations',
                'regex'       => '/^[a-z0-9]([_.-]?[a-z0-9]+)*/[a-z0-9](([_.]|-{1,2})?[a-z0-9]+)*$/',
            ],
            [
                'type'        => TypeEnum::Ask,
                'replace'     => ':description:',
                'question'    => 'Package description:',
                'placeholder' => 'E.g. My coolest project',
            ],
            [
                'type'        => TypeEnum::Ask,
                'replace'     => ':homepage:',
                'question'    => 'Which is homepage URL for the project?',
                'placeholder' => 'E.g. https://dragon-code.pro',
            ],
            [
                'type'     => TypeEnum::Ask,
                'replace'  => 'DummyNamespace',
                'question' => 'Which kind of namespace do you want to use?',
                'regex'    => '/^[a-zA-Z0-9_\\]+$/',
            ],
            [
                'type'    => TypeEnum::Author,
                'replace' => [':author:'],
            ],
            [
                'type'    => TypeEnum::License,
                'default' => 'mit',
                'replace' => ':license:',
            ],
        ],
    ],
];

<?php

declare(strict_types=1);

use PackageWizard\Installer\Enums\TypeEnum;

return [
    'filename' => 'wizard.json',

    'default' => [
        'wizard' => [
            'install' => [
                'composer' => true,
                'npm'      => false,
                'yarn'     => false,
            ],

            'clean' => true,
        ],

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
                'placeholder' => 'E.g. Deploy Operations',
            ],
            [
                'type'        => TypeEnum::Ask,
                'replace'     => ':description:',
                'question'    => 'Which description of your project?',
                'placeholder' => 'E.g. My coolest project',
            ],
            [
                'type'        => TypeEnum::Ask,
                'replace'     => ':package:',
                'question'    => 'Which name of your package?',
                'placeholder' => 'E.g. dragon-code/laravel-deploy-operations',
                'regex'       => '#^[a-z0-9]([_.-]?[a-z0-9]+)*/[a-z0-9](([_.]|-{1,2})?[a-z0-9]+)*$#',
            ],
            [
                'type'        => TypeEnum::Ask,
                'replace'     => ':repository:',
                'question'    => 'Which link to your repository?',
                'placeholder' => 'E.g. https://github.com/foo/bar',
                'regex'       => '#^https:\/\/[a-zA-Z\d_\-.]+\/[a-zA-Z\d_\-.]+\/[a-zA-Z\d_\-.]+$#',
            ],
            [
                'type'     => TypeEnum::Ask,
                'replace'  => ':homepage:',
                'question' => 'Which is homepage URL for the project?',
                'required' => false,
            ],
            [
                'type'     => TypeEnum::Ask,
                'replace'  => 'DummyNamespace',
                'question' => 'Which kind of namespace do you want to use?',
                'regex'    => '#^[a-zA-Z0-9_\\\]+$#',
                'required' => 'Namespace can\'t be empty',
            ],
            [
                'type'    => TypeEnum::Author,
                'replace' => [':author:'],
            ],
            [
                'type'     => TypeEnum::License,
                'default'  => 'MIT License',
                'replace'  => ':license:',
                'filename' => 'LICENSE',
            ],
        ],
    ],
];

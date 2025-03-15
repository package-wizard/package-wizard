<?php

declare(strict_types=1);

use PackageWizard\Installer\Data\Normalizers\JsonNormalizer;

return [
    'date_format' => \DATE_ATOM,

    'date_timezone' => null,

    'features' => [
        'cast_and_transform_iterables' => true,

        'ignore_exception_when_trying_to_set_computed_property_value' => false,
    ],

    'transformers' => [
        DateTimeInterface::class                      => Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer::class,
        Illuminate\Contracts\Support\Arrayable::class => Spatie\LaravelData\Transformers\ArrayableTransformer::class,
        BackedEnum::class                             => Spatie\LaravelData\Transformers\EnumTransformer::class,
    ],

    'casts' => [
        DateTimeInterface::class => Spatie\LaravelData\Casts\DateTimeInterfaceCast::class,
        BackedEnum::class        => Spatie\LaravelData\Casts\EnumCast::class,
    ],

    'rule_inferrers' => [
        Spatie\LaravelData\RuleInferrers\SometimesRuleInferrer::class,
        Spatie\LaravelData\RuleInferrers\NullableRuleInferrer::class,
        Spatie\LaravelData\RuleInferrers\RequiredRuleInferrer::class,
        Spatie\LaravelData\RuleInferrers\BuiltInTypesRuleInferrer::class,
        Spatie\LaravelData\RuleInferrers\AttributesRuleInferrer::class,
    ],

    'normalizers' => [
        // Spatie\LaravelData\Normalizers\ModelNormalizer::class,
        // Spatie\LaravelData\Normalizers\FormRequestNormalizer::class,
        Spatie\LaravelData\Normalizers\ArrayableNormalizer::class,
        Spatie\LaravelData\Normalizers\ObjectNormalizer::class,
        Spatie\LaravelData\Normalizers\ArrayNormalizer::class,
        JsonNormalizer::class,
    ],

    'wrap' => null,

    'var_dumper_caster_mode' => 'development',

    'structure_caching' => [
        'enabled'     => true,
        'directories' => [app_path('Data')],
        'cache'       => [
            'store'    => env('CACHE_STORE', 'array'),
            'prefix'   => 'laravel-data',
            'duration' => null,
        ],
        'reflection_discovery' => [
            'enabled'        => true,
            'base_path'      => base_path(),
            'root_namespace' => null,
        ],
    ],

    'validation_strategy' => Spatie\LaravelData\Support\Creation\ValidationStrategy::OnlyRequests->value,

    'name_mapping_strategy' => [
        'input'  => null,
        'output' => null,
    ],

    'ignore_invalid_partials' => false,

    'max_transformation_depth' => null,

    'throw_when_max_transformation_depth_reached' => true,

    'commands' => [
        'make' => [
            'namespace' => 'Data',
            'suffix'    => 'Data',
        ],
    ],
];

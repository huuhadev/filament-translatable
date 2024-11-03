<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Translation Service
    |--------------------------------------------------------------------------
    */
    'translator' => [
        'default' => \Huuhadev\FilamentTranslatable\Services\Translators\GoogleTranslator::class,
        'timeout' => env('FILAMENT_TRANSLATOR_TIMEOUT', 30),
    ],
];

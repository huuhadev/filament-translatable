<?php

namespace Huuhadev\FilamentTranslatable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getName()
 * @method static string translate(string $text, array $replace = [], ?string $to = null)
 *
 * @see \Huuhadev\FilamentTranslatable\Services\TranslateService
 */
class FilamentTranslator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-translatable';
    }
}

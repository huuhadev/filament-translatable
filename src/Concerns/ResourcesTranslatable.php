<?php

namespace Huuhadev\FilamentTranslatable\Concerns;

use Exception;
use Spatie\Translatable\HasTranslations;

trait ResourcesTranslatable
{
    public static function getDefaultTranslatableLocale(): string
    {
        return filament('filament-translatable')->getActiveLocale();
    }

    public static function getTranslatableAttributes(): array
    {
        $model = static::getModel();

        if (! method_exists($model, 'getTranslatableAttributes')) {
            throw new Exception("Model [{$model}] must use trait [" . HasTranslations::class . '].');
        }

        $attributes = app($model)->getTranslatableAttributes();

        if (! count($attributes)) {
            throw new Exception("Model [{$model}] must have [\$translatable] properties defined.");
        }

        return $attributes;
    }

    public static function getTranslatableLocales(): array
    {
        return filament('filament-translatable')->getLocales();
    }
}
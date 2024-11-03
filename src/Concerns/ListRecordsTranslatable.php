<?php

namespace Huuhadev\FilamentTranslatable\Concerns;

trait ListRecordsTranslatable
{
    use HasSelectLocale;

    public function mount(): void
    {
        $this->activeLocale = static::getResource()::getDefaultTranslatableLocale();
    }

    public function getTranslatableLocales(): array
    {
        return static::getResource()::getTranslatableLocales();
    }

    public function getActiveTableLocale(): ?string
    {
        return $this->activeLocale;
    }
}

<?php

namespace Huuhadev\FilamentTranslatable\Concerns;

trait RelationManagersTranslatable
{
    use HasSelectLocale;

    public function mount(): void
    {
        if (
            blank($this->activeLocale) ||
            (! in_array($this->activeLocale, $this->getTranslatableLocales()))
        ) {
            $this->setActiveLocale();
        }
    }

    public function getTranslatableLocales(): array
    {
        return filament('filament-translatable')->getLocales();
    }

    public function getDefaultTranslatableLocale(): string
    {
        return $this->getTranslatableLocales()[0];
    }

    public function getActiveTableLocale(): ?string
    {
        return $this->activeLocale;
    }

    protected function setActiveLocale(): void
    {
        $this->activeLocale = $this->getDefaultTranslatableLocale();
    }
}

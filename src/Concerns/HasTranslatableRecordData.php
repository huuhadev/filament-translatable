<?php

namespace Huuhadev\FilamentTranslatable\Concerns;

use Livewire\Attributes\Locked;

trait HasTranslatableRecordData
{
    #[Locked]
    public $otherLocaleData = [];

    protected function fillForm(): void
    {
        $this->activeLocale = $this->getDefaultTranslatableLocale();

        $record = $this->getRecord();
        $translatableAttributes = static::getResource()::getTranslatableAttributes();

        foreach ($this->getTranslatableLocales() as $locale) {
            $translatedData = [];

            foreach ($translatableAttributes as $attribute) {
                $translated = $record->getTranslation($attribute, $locale, useFallbackLocale: false);
                $translatedData[$attribute] = $translated;
            }

            if ($locale !== $this->activeLocale) {
                $this->otherLocaleData[$locale] = $this->mutateFormDataBeforeFill($translatedData);

                continue;
            }

            /** @internal Read the DocBlock above the following method. */
            $this->fillFormWithDataAndCallHooks($record, $translatedData);
        }
    }

    protected function getDefaultTranslatableLocale(): string
    {
        $resource = static::getResource();
        $translatableAttributes = $resource::getTranslatableAttributes();
        $availableLocales = array_keys($this->getRecord()->getTranslations($translatableAttributes[0]));
        $defaultLocale = $resource::getDefaultTranslatableLocale();

        return in_array($defaultLocale, $availableLocales)
            ? $defaultLocale
            : (array_intersect($availableLocales, $this->getTranslatableLocales())[0] ?? $defaultLocale);
    }
}

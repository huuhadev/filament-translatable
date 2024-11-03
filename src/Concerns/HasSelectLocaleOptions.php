<?php

namespace Huuhadev\FilamentTranslatable\Concerns;

use Huuhadev\FilamentTranslatable\FilamentTranslatablePlugin;

trait HasSelectLocaleOptions
{
    public function setTranslatableLocaleOptions(): static
    {
        $this->options(function (): array {
            $livewire = $this->getLivewire();

            if (! method_exists($livewire, 'getTranslatableLocales')) {
                return [];
            }

            $locales = [];

            /** @var FilamentTranslatablePlugin $plugin */
            $plugin = filament('filament-translatable');

            foreach ($livewire->getTranslatableLocales() as $locale) {
                $locales[$locale] = $plugin->getLabel($locale) ?? $locale;
            }

            return $locales;
        });

        return $this;
    }
}

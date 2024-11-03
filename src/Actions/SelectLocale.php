<?php

namespace Huuhadev\FilamentTranslatable\Actions;

use Filament\Actions\SelectAction;
use Huuhadev\FilamentTranslatable\Concerns\HasSelectLocaleOptions;

class SelectLocale extends SelectAction
{
    use HasSelectLocaleOptions;

    public static function getDefaultName(): ?string
    {
        return 'activeLocale';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-translatable::lang.actions.label'));

        $this->setTranslatableLocaleOptions();

        $this->disabled(fn() => count($this->getOptions()) <= 1);
    }
}

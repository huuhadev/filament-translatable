<?php

namespace Huuhadev\FilamentTranslatable\Concerns;

use Illuminate\Support\Arr;

trait ViewRecordTranslatable
{
    use HasSelectLocale;
    use HasTranslatableRecordData;
    use HasTranslatableRecord;

    protected ?string $oldActiveLocale = null;

    public function updatingActiveLocale(): void
    {
        $this->oldActiveLocale = $this->activeLocale;
    }

    public function updatedActiveLocale(string $newActiveLocale): void
    {
        if (blank($this->oldActiveLocale)) {
            return;
        }

        $translatableAttributes = static::getResource()::getTranslatableAttributes();

        $this->otherLocaleData[$this->oldActiveLocale] = Arr::only($this->data, $translatableAttributes);
        $this->data = [
            ...$this->data,
            ...$this->otherLocaleData[$this->activeLocale] ?? [],
        ];
    }

    public function getTranslatableLocales(): array
    {
        return static::getResource()::getTranslatableLocales();
    }
}

<?php

namespace Huuhadev\FilamentTranslatable\Events;

class LocalizationChangedEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $locale
    ) {
    }
}

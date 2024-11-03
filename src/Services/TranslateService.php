<?php

namespace Huuhadev\FilamentTranslatable\Services;

use Huuhadev\FilamentTranslatable\Contracts\Translator;

class TranslateService
{
    private Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getName(): string
    {
        return $this->translator->getName();
    }

    public function translate(string $text, string $source, string $to): string
    {
        return $this->translator->translate($text, $source, $to);
    }
}

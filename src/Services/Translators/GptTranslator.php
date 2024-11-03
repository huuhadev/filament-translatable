<?php

namespace Huuhadev\FilamentTranslatable\Services\Translators;

use Huuhadev\FilamentTranslatable\Contracts\Translator;

class GptTranslator implements Translator
{
    public function getName(): string
    {
        return __('GPT Translate');
    }

    public function translate(string $text, string $source, string $target): string
    {
        return $text;
    }
}

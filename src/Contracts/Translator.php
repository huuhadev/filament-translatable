<?php

namespace Huuhadev\FilamentTranslatable\Contracts;

interface Translator
{
    public function getName(): string;
    public function translate(string $text, string $source, string $target): string;
}

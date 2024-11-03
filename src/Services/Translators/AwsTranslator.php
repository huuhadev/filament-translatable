<?php

namespace Huuhadev\FilamentTranslatable\Services\Translators;

use Aws\Laravel\AwsFacade;
use Aws\Translate\TranslateClient;
use Huuhadev\FilamentTranslatable\Contracts\Translator;

class AwsTranslator implements Translator
{
    public function getName(): string
    {
        return __('Aws Translate');
    }

    public function translate(string $text, string $source, string $to): string
    {
        $aws = AwsFacade::createClient('translate', [
            'http' => [
                'timeout' => config('filament-translatable.translator.timeout', 30),
            ],
        ]);

        /** @var TranslateClient $aws */
        return $aws->translateText([
            'Text' => $text,
            'SourceLanguageCode' => $source,
            'TargetLanguageCode' => $to,
        ]);
    }
}

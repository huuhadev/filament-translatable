<?php

namespace Huuhadev\FilamentTranslatable\Services\Translators;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Huuhadev\FilamentTranslatable\Contracts\Translator;

class GoogleTranslator implements Translator
{
    protected string $url;

    public function __construct()
    {
        $this->url = 'https://translate.googleapis.com/translate_a/single?client=gtx&dt=t';
    }

    public function getName(): string
    {
        return __('Google Translate');
    }

    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    public function translate(string $text, string $source, string $target): string
    {
        $response = Http::asForm()
            ->post($this->url, [
                'sl' => $source,
                'tl' => $target,
                'q' => $text,
            ])->body();

        return self::getTranslatedText($response);
    }

    protected static function getTranslatedText($json): string
    {
        $response = json_decode($json, true);

        if (! $response || ! isset($response[0])) {
            throw new \Exception('Google detected unusual traffic from your computer network, try again later (2 - 48 hours)');
        }

        $translatedText = '';
        foreach ($response[0] as $translation) {
            $translatedText .= $translation[0] ?? '';
        }

        return $translatedText;
    }
}

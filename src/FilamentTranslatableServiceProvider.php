<?php

namespace Huuhadev\FilamentTranslatable;

use InvalidArgumentException;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Huuhadev\FilamentTranslatable\Contracts\Translator;
use Huuhadev\FilamentTranslatable\Services\TranslateService;
use Huuhadev\FilamentTranslatable\Services\Translators\GoogleTranslator;

class FilamentTranslatableServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-translatable';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations()
            ->hasAssets()
            ->hasViews()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishAssets()
                    ->askToStarRepoOnGitHub('huuhadev/filament-translatable');
            });
    }

    public function packageBooted(): void
    {
        $this->app->singleton('filament-translatable', function (): TranslateService {
            $translator = $this->resolveTranslator();
            return new TranslateService($translator);
        });

        FilamentAsset::register([
            Css::make('language-switcher-css', __DIR__ . '/../resources/css/switcher.css'),
        ], 'huuhadev/filament-translatable');
    }

    private function resolveTranslator(): Translator
    {
        $translatorClass = config('filament-translatable.translator.default', GoogleTranslator::class);

        if (! class_exists($translatorClass)) {
            throw new InvalidArgumentException("Invalid translator class: {$translatorClass}");
        }

        $translatorInstance = new $translatorClass();

        if (! $translatorInstance instanceof Translator) {
            throw new InvalidArgumentException("The class {$translatorClass} must implement Huuhadev\FilamentTranslatable\Contracts\Translator");
        }

        return $translatorInstance;
    }
}

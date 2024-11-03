<?php

namespace Huuhadev\FilamentTranslatable;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\Support\Htmlable;
use Huuhadev\FilamentTranslatable\Components\Translator;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\View\PanelsRenderHook;
use Huuhadev\FilamentTranslatable\Components\Switcher;
use Huuhadev\FilamentTranslatable\Enums\DropdownPlacement;
use Huuhadev\FilamentTranslatable\Http\Middleware\Localization;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class FilamentTranslatablePlugin implements Plugin
{
    use EvaluatesClosures;

    protected array|Closure $locales = [];

    protected array|Closure $labels = [];

    protected array|Closure $flags = [];

    protected ?string $displayLocale = null;

    protected string $buttonClasses = 'rounded-lg';

    protected string $size = 'md';

    protected bool $onlyFlag = false;

    protected bool $rounded = true;

    protected string|Closure|null $renderUsingHook = null;

    protected DropdownPlacement $placement = DropdownPlacement::BottomEnd;

    final public function __construct() {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): Plugin|\Filament\FilamentManager
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'filament-translatable';
    }

    public function register(Panel $panel): void
    {
        if ($this->isVisible()) {
            $panel
                ->renderHook(
                    name: $this->getRenderHook(),
                    hook: fn (): string => Blade::render("@livewire('switcher')")
                );
        }

        $this->addLocalizationMiddlewareToPanel($panel);
    }

    public function boot(Panel $panel): void
    {
        Field::macro('translator', function (
            string | Htmlable | Closure | null $label = null
        ): RichEditor| Textarea| TextInput {
            return Translator::action($this, $label);
        });

        Livewire::component('switcher', Switcher::class);
    }

    public function isVisible(): bool
    {
        return count($this->locales) > 1;
    }

    public function renderHook(string|Closure $panelHook): static
    {
        $this->renderUsingHook = $panelHook;

        return $this;
    }

    public function displayLocale(string $displayLocale): static
    {
        $this->displayLocale = $displayLocale;

        return $this;
    }

    public function getDisplayLocale(): ?string
    {
        return $this->displayLocale;
    }

    public function getRenderHook(): string
    {
        return $this->evaluate($this->renderUsingHook) ?? PanelsRenderHook::GLOBAL_SEARCH_AFTER;
    }

    public function labels(array|Closure $labels): static
    {
        $this->labels = $labels;

        return $this;
    }

    public function locales(array|Closure $locales): static
    {
        if (array_is_list($locales)) {
            $this->locales = $locales;
        } else {
            $this->locales = array_keys($locales);

            $this->flags = $locales;
        }

        return $this;
    }

    public function size(string $size = ''): static
    {
        $this->size = $size;

        return $this;
    }

    public function getFlag(string $locale): string
    {
        return $this->flags[$locale] ?? false;
    }

    public function isOnlyFlag(): bool
    {
        return $this->onlyFlag;
    }

    public function onlyFlag(bool $condition = true): static
    {
        $this->onlyFlag = $condition;

        return $this;
    }

    public function getLocales(): array
    {
        return (array) $this->evaluate($this->locales);
    }

    public function getActiveLocale(): string
    {
        $locale = session()->get('locale') ??
            request()->get('locale') ??
            request()->cookie('filament_language_switch_locale') ??
            config('app.locale');

        return in_array($locale, $this->getLocales(), true) ? $locale : config('app.locale');
    }

    public function getLabel(string $locale): ?string
    {
        $displayLocale = $this->getDisplayLocale();

        if (array_key_exists($locale, $this->labels)) {
            return strval($this->labels[$locale]);
        }

        return str(locale_get_display_name(locale: $locale, displayLocale: $displayLocale))->title()->toString();
    }

    public function buttonClasses(string $classes): static
    {
        $this->buttonClasses = $classes;

        return $this;
    }

    public function getButtonClasses(): string
    {
        return $this->buttonClasses;
    }
    public function rounded(bool $condition = true): static
    {
        $this->rounded = $condition;

        return $this;
    }

    public function isRounded(): bool
    {
        return $this->rounded;
    }

    public function placement(DropdownPlacement $placement): static
    {
        $this->placement = $placement;

        return $this;
    }

    public function getPlacement(): DropdownPlacement
    {
        if ($this->isOnlyFlag()) {
            return DropdownPlacement::Bottom;
        }

        return $this->placement;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    protected function addLocalizationMiddlewareToPanel(Panel $panel): void
    {
        $middlewares = invade($panel)->getMiddleware();
        $localizationMiddleware = Localization::class;
        $referenceMiddleware = DispatchServingFilamentEvent::class;

        $position = array_search($referenceMiddleware, $middlewares);
        if ($position !== false) {
            array_splice($middlewares, $position, 0, $localizationMiddleware);
        }

        invade($panel)->middleware = $middlewares;
    }
}

<?php

namespace Huuhadev\FilamentTranslatable\Components;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Huuhadev\FilamentTranslatable\Events\LocalizationChangedEvent;

class Switcher extends Component
{
    public function change($locale)
    {
        session()->put('locale', $locale);

        cookie()->queue(cookie()->forever('filament_translatable_switcher_locale', $locale));

        event(new LocalizationChangedEvent($locale));

        return redirect(request()->header('Referer'));
    }

    public function render(): View
    {
        return view('filament-translatable::components.switcher');
    }
}

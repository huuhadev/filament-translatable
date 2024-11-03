<?php

namespace Huuhadev\FilamentTranslatable\Components;

use Closure;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Core\Department;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Group;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Actions\Action;
use Huuhadev\FilamentTranslatable\FilamentTranslatablePlugin;
use Huuhadev\FilamentTranslatable\Facades\FilamentTranslator;

class Translator
{
    /**
     * Translator action
     *
     * @param RichEditor|TextInput|Textarea $component
     * @param string|Htmlable|Closure|null $label
     * @return RichEditor|Textarea|TextInput
     */
    public static function action(
        RichEditor|TextInput|Textarea  $component,
        string | Htmlable | Closure | null $label
    ): RichEditor|Textarea|TextInput
    {
        /** @var FilamentTranslatablePlugin $plugin */
        $plugin = filament('filament-translatable');
        if (!$plugin->isVisible()){
            return $component;
        }

        return $component->hintAction(function ($component) use ($plugin, $label) {
            return Action::make('translate')
                ->icon('heroicon-o-language')
                ->label($label)
                ->modalWidth(MaxWidth::TwoExtraLarge)
                ->modalIcon('heroicon-o-language')
                ->modalHeading(FilamentTranslator::getName())
                ->modalSubmitActionLabel(__('filament-translatable::lang.translation.submit'))
                ->modalAlignment(Alignment::Center)
                ->closeModalByClickingAway(false)
                ->form(fn (Form $form) => Translator::form($form, $component, $plugin))
                ->action(function (array $data, $component, $livewire) {
                    $translatedText = $data['translated_text'];
                    $component->state($translatedText);
                    if ($component instanceof RichEditor){
                        $livewire->dispatch('refresh-tiptap-editors', [
                            'statePath' => $component->getName(),
                            'content' => $translatedText,
                        ]);
                    }
                });
        });
    }

    /**
     *  Translator form
     *
     * @param Form $form
     * @param RichEditor|Textarea|TextInput $component
     * @param FilamentTranslatablePlugin $plugin
     * @return Form
     */
    public static function form(Form $form, RichEditor|Textarea|TextInput $component, FilamentTranslatablePlugin $plugin): Form
    {
        $translations = fn ($record) => collect($record->getTranslations($component->getName()));

        return $form->schema([
            Group::make()->schema([
                Select::make('source')
                    ->hiddenLabel()
                    ->placeholder(__('filament-translatable::lang.translation.source'))
                    ->options(function (Livewire $livewire, ?Department $record) use ($translations, $plugin) {
                        return $translations($record)
                            ->filter(fn ($translated, $locale) => $locale !== $livewire->activeLocale)
                            ->mapWithKeys(fn ($translated, $locale) => [$locale => $plugin->getLabel($locale) ?? $locale])
                            ->toArray();
                    })
                    ->afterStateUpdated(function (Set $set, $state, $record, $livewire) use ($component, $plugin) {
                        if (empty($state)){
                            return;
                        }

                        $text = $record->translate($component->getName(), $state);
                        $target = $livewire->activeLocale;
                        $set('source_text', $text);

                        try {
                            $translatedText = FilamentTranslator::translate($text, $state, $livewire->activeLocale);
                            $set('translated_text', $translatedText);
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title(
                                    __('filament-translatable::lang.translation.error.title', [
                                        'source' => $plugin->getLabel($state),
                                        'target' => $plugin->getLabel($target),
                                    ])
                                )
                                ->body(
                                    __('filament-translatable::lang.translation.error.content', [
                                        'exception' => $e->getMessage()
                                    ])
                                )
                                ->danger()
                                ->send();
                        }
                    })
                    ->reactive(),

                Textarea::make('source_text')
                    ->hiddenLabel()
                    ->readOnly()
                    ->placeholder(__('filament-translatable::lang.translation.source_text'))
                    ->rows(5),

            ])->columns(1),

            Group::make()->schema([
                TextInput::make('target')
                    ->hiddenLabel()
                    ->placeholder(__('filament-translatable::lang.translation.target'))
                    ->readOnly()
                    ->disabled()
                    ->default(fn ($livewire) => $plugin->getLabel($livewire->activeLocale)),

                Textarea::make('translated_text')
                    ->hiddenLabel()
                    ->rows(5)
                    ->required()
                    ->placeholder(__('filament-translatable::lang.translation.translated_text')),

            ])->columns(1),
        ])->columns();
    }
}

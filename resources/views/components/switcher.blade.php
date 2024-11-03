@php
    $currentLocale = app()->getLocale();
    $plugin = filament('filament-translatable');
    $rounded = $plugin->isRounded();
    $label = $plugin->getLabel($currentLocale);
    $onlyFlag = $plugin->isOnlyFlag();
    $locales = $plugin->getLocales();
    $size = $plugin->getSize();
    $placement = $plugin->getPlacement()->value;
$buttonClasses = $plugin->getButtonClasses();
@endphp

<div class="language-switcher-component">
@if($plugin->isVisible())
    <x-filament::dropdown
        teleport
        :placement="$placement"
        class="fi-dropdown fi-language-switcher"
        :width="$onlyFlag ? '!max-w-[3rem]' : '!max-w-[10rem]'"
    >
        <x-slot name="trigger">
            <div
                @class([
                    'flex items-center justify-center gap-x-2 p-1 hover:bg-gray-100 dark:hover:bg-white/5',
                    $buttonClasses
                ])
                x-tooltip="{
                    content: @js($label),
                    theme: $store.theme,
                    placement: 'bottom'
                }"
            >
                @if ($flag = $plugin->getFlag($currentLocale))
                    <x-filament::avatar
                        :src="$flag"
                        :size="$size"
                        :circular="$rounded"
                        :alt="$label"
                    />
                @else
                    <span
                        @class([
                            'flex items-center justify-center flex-shrink-0 p-2 text-xs font-semibold bg-primary-500 text-white',
                            $size,
                            'rounded-full' => $rounded,
                        ])
                    >
                        {{ getCharAvatar($currentLocale) }}
                    </span>
                @endif

                @if(!$onlyFlag)
                    <span class="flex-1 text-left text-primary-600 dark:text-primary-400">
                        {{ $label }}
                    </span>
                    <div class="icon h-6 w-6 flex justify-center items-center mr-4 text-primary-600 dark:text-primary-400">
                        <x-filament::icon
                            icon="heroicon-m-chevron-down"
                            clss="h-5 w-5 text-gray-600 dark:text-gray-200"
                        />
                    </div>
                @endif
            </div>
        </x-slot>

        <x-filament::dropdown.list
            @class([
                'flex flex-col p-2',
                'gap-y-2' => $onlyFlag
            ])
        >
            @foreach ($locales as $locale)
                @if (!app()->isLocale($locale))
                    <button type="button"
                        wire:click="change('{{ $locale }}')"
                        @if ($onlyFlag)
                            x-tooltip="{
                                content: @js($plugin->getLabel($locale)),
                                theme: $store.theme,
                                placement: 'right'
                            }"
                        @endif

                        @class([
                            'text-gray-700 dark:text-gray-200 text-sm font-medium flex items-center hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5',
                            'flex-col rounded-full w-8 h-8 justify-center' => $onlyFlag,
                            'justify-start gap-x-2 rounded-lg p-2 rtl:space-x-reverse' => !$onlyFlag,
                        ])
                    >
                        @if ($flag = $plugin->getFlag($locale))
                            <x-filament::avatar
                                :src="$flag"
                                size="sm"
                                :circular="$rounded"
                                :alt="$plugin->getLabel($locale)"
                            />
                        @else
                            <span
                                @class([
                                    'flex items-center w-7 h-7 justify-center text-xs font-semibold bg-primary-500 text-white dark:hover:bg-primary-500/10',
                                    'rounded-full' => $rounded,
                                    'rounded-md' => !$rounded,
                                ])
                                >
                                    {{ getCharAvatar($locale) }}
                                </span>
                        @endif

                        @if (!$onlyFlag)
                            <span class="text-sm font-medium"> {{ $plugin->getLabel($locale) }} </span>
                        @endif
                    </button>
                @endif
            @endforeach
        </x-filament::dropdown.list>

    </x-filament::dropdown>
@endif
</div>

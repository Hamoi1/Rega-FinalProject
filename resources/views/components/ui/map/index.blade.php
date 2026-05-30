@props([
    'marks' => [],
    'zoomLevel' => 16,
    'id' => 'defaultMapId',
    'attribution' => '',
    'disableMarkAction' => false,
    'hasMultiple' => false,
    'label' => '',
    'centerPoint' => [],
    'geoJson' => null,
    'geoJsonUrl' => null,
    'routeStartName' => null,
    'routeEndName' => null,
    'modalName' => null,
])

@php
    $wireModel = $attributes->wire('model')->value();
@endphp

<div class="w-full h-full space-y-4">
    @if ($label)
        <label for="{{ $id }}"
            class="block font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    @endif
    @error($hasMultiple ? $wireModel . '.*' : $wireModel)
        <span class="text-sm text-negative-600 ">{{ $message }}</span>
    @enderror
    <div class="w-full h-full relative overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-100 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
        x-data="{
            ...MapComponent({
                id: @js($id),
                attribution: @js($attribution),
                marks: @js($marks),
                centerPoint: @js($centerPoint),
                zoomLevel: @js($zoomLevel),
                hasMultiple: @js($hasMultiple),
                disableMarkAction: @js($disableMarkAction),
                model: @js($wireModel),
                geoJson: @js($geoJson),
                geoJsonUrl: @js($geoJsonUrl),
                routeStartName: @js($routeStartName),
                routeEndName: @js($routeEndName),
                modalName: @js($modalName),
                locale: @js(app()->getLocale()),
            }),
        }" x-init="initMap();" wire:ignore>

        <div id="{{ $id }}" class="w-full h-full min-h-[18rem] z-0"></div>

        <div class="absolute bottom-4 right-4 z-20 flex flex-col items-end gap-3" x-data="{ showStyles: false }"
            @click.outside="showStyles = false">
            <div class="relative">
                <button type="button" @click="showStyles = !showStyles" aria-label="{{ __('words.change_style') }}"
                    class="w-9 h-9 sm:w-11 sm:h-11 bg-white/95 dark:bg-zinc-900/95 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-800 flex items-center justify-center transition-all hover:scale-105 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-zinc-900"
                    title="{{ __('words.change_style') }}">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </button>

                <div x-show="showStyles" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                    class="absolute bottom-14 right-0 bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 p-2 min-w-[160px] flex flex-col gap-1">
                    <template x-for="style in mapStyles" :key="style.id">
                        <button type="button" @click="setMapStyle(style.id); showStyles = false"
                            class="w-full text-left px-3 py-2 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-zinc-900"
                            :class="currentMapStyle === style.id ?
                                'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' :
                                'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800'">
                            <span x-text="style.name"></span>
                            <svg x-show="currentMapStyle === style.id" class="w-4 h-4 ml-auto" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Modern Loading indicator --}}
        <div class="absolute top-24 left-1/2 -translate-x-1/2 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-md rounded-full shadow-xl border border-white/20 dark:border-zinc-700 py-2 px-4 flex items-center gap-3 z-20"
            x-show="isLoading" style="display: none;" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4">
            <div class="relative w-5 h-5">
                <div
                    class="absolute inset-0 rounded-full border-2 border-orange-500/20 border-t-orange-500 animate-spin">
                </div>
            </div>
            <span
                class="text-sm font-bold text-zinc-700 dark:text-zinc-200 tracking-wide">{{ __('words.syncing_data') }}</span>
        </div>
    </div>
</div>

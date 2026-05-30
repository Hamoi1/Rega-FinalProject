<div class="min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 pt-32 pb-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

        <div class="flex items-start justify-between gap-6 flex-col">
            <div class="min-w-0">
                <p class="text-xs font-black uppercase tracking-widest text-zinc-400">{{ __('words.title') }}</p>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-900 dark:text-white truncate">
                    {{ $stop->getTranslation('name', app()->getLocale()) ?? __('words.unknown') }}
                </h1>
                <div class="mt-2 flex flex-wrap items-center gap-3 text-sm font-bold text-zinc-600 dark:text-zinc-300">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        {{ $stop->city?->getTranslation('name', app()->getLocale()) ?? '-' }}
                    </span>
                    @php
                        $coords = is_array($stop->map_location) ? $stop->map_location : [];
                        $lat = data_get($coords, 'lat');
                        $lng = data_get($coords, 'lng');
                    @endphp
                    @if (is_numeric($lat) && is_numeric($lng))
                        <span class="text-zinc-400">•</span>
                        <span>{{ __('words.coordinates', ['lat' => number_format((float) $lat, 6), 'lng' => number_format((float) $lng, 6)]) }}</span>
                    @endif
                </div>
                <div class="mt-3 text-sm font-bold text-zinc-500 dark:text-zinc-400">
                    {{ __('words.lines_hint') }}
                </div>
            </div>

            <div class="shrink-0 flex items-center gap-2">
                <a href="{{ route('map', ['from' => $stop->id]) }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-blue-600 text-white font-extrabold shadow-lg shadow-blue-500/25 hover:bg-blue-700 transition-colors">
                    {{ __('words.open_in_map') }}
                </a>
                @auth
                    <button type="button" wire:click="toggleFavorite"
                        class="inline-flex items-center justify-center w-11 h-11 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors"
                        aria-label="{{ $is_favorite ? __('words.remove_from_favorites') : __('words.add_to_favorites') }}"
                        title="{{ $is_favorite ? __('words.remove_from_favorites') : __('words.add_to_favorites') }}">
                        @if ($is_favorite)
                            <svg class="w-6 h-6 text-yellow-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        @else
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                aria-hidden="true">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        @endif
                    </button>
                @endauth
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-extrabold">{{ __('words.lines') }}</h2>
                        <span class="text-xs font-black text-zinc-400">{{ count($lines) }}</span>
                    </div>

                    @if (count($lines))
                        <div class="mt-4 space-y-3">
                            @foreach ($lines as $line)
                                <a href="{{ route('map', ['city' => $stop->city_id, 'location' => $line['from_id'], 'from' => $line['from_id'], 'to' => $line['to_id']]) }}"
                                    class="block rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/30 p-4 hover:border-blue-500/30 hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="min-w-0">
                                            <div class="text-sm font-extrabold truncate">
                                                {{ data_get($line, 'from_name') }} <span class="text-zinc-400">→</span>
                                                {{ data_get($line, 'to_name') }}
                                            </div>
                                            <div class="mt-1 text-xs font-black text-zinc-400">
                                                {{ __('words.open_line_on_map') }}
                                            </div>
                                        </div>
                                        <div
                                            class="shrink-0 w-9 h-9 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 flex items-center justify-center text-zinc-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-4 text-sm font-bold text-zinc-500">{{ __('words.no_lines') }}</div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6">
                    <h2 class="text-lg font-extrabold">{{ __('words.nearby') }}</h2>

                    @if (count($nearbyStops))
                        <div class="mt-4 space-y-3">
                            @foreach ($nearbyStops as $nearby)
                                <a href="{{ route('words.show', ['location' => data_get($nearby, 'id')]) }}"
                                    class="block rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/30 p-4 hover:border-blue-500/30 transition-colors">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="text-sm font-extrabold truncate">
                                                {{ data_get($nearby, 'name') }}</div>
                                            <div class="text-xs font-bold text-zinc-500">
                                                {{ __('words.distance_km', ['km' => number_format((float) data_get($nearby, 'distance_km', 0), 2)]) }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-4 text-sm font-bold text-zinc-500">{{ __('words.no_nearby') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

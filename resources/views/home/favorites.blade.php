<div class="relative bg-zinc-50 dark:bg-zinc-950 min-h-screen font-sans overflow-hidden pt-32 pb-20">

    {{-- Background Decoration --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div
            class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] dark:bg-[radial-gradient(#27272a_1px,transparent_1px)] [background-size:24px_24px] opacity-70">
        </div>
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-500/10 rounded-full blur-3xl -ml-20 -mb-20">
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span class="text-xs font-bold uppercase tracking-widest">{{ __('words.favorites') }}</span>
                </div>
                <h1 class="mt-4 text-4xl lg:text-6xl font-black text-zinc-900 dark:text-white tracking-tight">
                    {{ __('words.favorites') }}</h1>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400 font-medium">{{ __('words.stops') }}</p>
            </div>
        </div>

        @if ($favorites->count() === 0)
            <div class="text-center py-20">
                <div
                    class="mx-auto w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 5.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <p class="text-zinc-600 dark:text-zinc-400 font-medium">{{ __('words.nothing_to_show') }}</p>
                <div class="mt-6">
                    <a href="{{ route('map') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-bold transition-all shadow-lg shadow-blue-600/20 hover:scale-105 active:scale-95">
                        <span>{{ __('words.live_map') }}</span>
                        <span class="opacity-80">→</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($favorites as $fav)
                    <div
                        class="group p-5 rounded-2xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur border border-zinc-200/80 dark:border-zinc-800/80 hover:border-blue-200 dark:hover:border-blue-800 transition-all shadow-sm hover:shadow-lg">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <a href="{{ route('words.show', ['location' => $fav->location_id]) }}" wire:navigate
                                    class="block text-lg font-extrabold text-zinc-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $fav->location_name ?? __('words.unknown') }}
                                </a>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('words.location') }}</p>
                            </div>
                            <button type="button" wire:click="removeFavorite({{ (int) $fav->location_id }})"
                                class="shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors"
                                title="{{ __('words.remove_from_favorites') }}"
                                aria-label="{{ __('words.remove_from_favorites') }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1.01 4.22 2.61C11.59 5.01 13.26 4 15 4 17.5 4 19.5 6 19.5 8.5c0 3.78-3.4 6.86-8.05 11.54L12 21.35z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @endif
    </div>
    </section>

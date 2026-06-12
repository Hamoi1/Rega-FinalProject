<div class="relative w-full h-[100dvh] overflow-hidden bg-zinc-50 dark:bg-zinc-950 font-sans antialiased text-zinc-800 dark:text-zinc-200"
    x-data="{
        mobilePanelOpen: false,
        selectedRouteId: null,
        mapId: 'public-map',
        isMobile: window.innerWidth < 1024,
        init() {
            window.addEventListener('resize', () => { this.isMobile = window.innerWidth < 1024; });
            window.addEventListener('route:selected', (e) => {
                this.selectedRouteId = e.detail?.routeId ?? null;
                if (this.isMobile) this.mobilePanelOpen = false;
            });
        },
        selectRoute(routeFileUrl, routeStartName, routeEndName, routeId) {
            if (!routeFileUrl) return;
            this.selectedRouteId = routeId;
            window.dispatchEvent(new CustomEvent('load-map-data', {
                detail: {
                    mapId: this.mapId,
                    geoJsonUrl: routeFileUrl,
                    routeStartName: routeStartName,
                    routeEndName: routeEndName
                }
            }));
            if (this.isMobile) this.mobilePanelOpen = false;
        }
    }" x-init="$watch('mobilePanelOpen', () => {
        setTimeout(() => window.dispatchEvent(new CustomEvent('map:invalidate-size', { detail: { mapId: mapId } })), 300);
    });"
    @mount-map-data.window="
        const data = event.detail[0] || event.detail; 
        if(data) selectRoute(data.geoJsonUrl, data.routeStartName, data.routeEndName, data.routeId);
    ">

    <div class="flex flex-col lg:flex-row h-full w-full">
        {{-- SIDEBAR PANEL --}}
        <div class="shrink-0 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl z-30 flex flex-col shadow-2xl lg:shadow-xl lg:border-r border-zinc-200 dark:border-zinc-800 transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) absolute bottom-0 w-full rounded-t-3xl h-[calc(100%-10px)] lg:relative lg:w-[420px] lg:h-full lg:rounded-none"
            :class="mobilePanelOpen ? 'translate-y-0' : 'translate-y-full lg:translate-y-0'" x-cloak>

            {{-- HEADER & FILTERS --}}
            <div class="p-4 lg:pt-6 shrink-0 space-y-5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3 group cursor-pointer" wire:navigate href="{{ route('home') }}">
                        <div
                            class="shrink-0 size-12 rounded-2xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center shadow-sm transition-transform duration-300 group-hover:scale-105">
                            <img src="{{ getCompanyLogo() }}" alt="Logo" class="size-8">
                        </div>
                        <div class="flex flex-col justify-center">
                            <h1
                                class="font-extrabold text-xl text-zinc-900 dark:text-white leading-none tracking-tight">
                                {{ setting()->get('company_name', 'Rega') }}</h1>
                            <span
                                class="text-[10px] font-black text-blue-600 dark:text-blue-400 tracking-widest uppercase mt-1">{{ __('words.map') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <x-switch-language />
                        <x-switch-theme />
                        <button @click="mobilePanelOpen = false"
                            class="lg:hidden size-8 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-500 flex items-center justify-center hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="relative">
                    <x-wireui-input type="search" wire:model.live.debounce.300ms="search"
                        placeholder="{{ __('words.search', ['attr' => __('pages.bus_lines.single')]) }}..."
                        class="w-full bg-zinc-100 dark:bg-zinc-800 border-0 rounded-2xl font-bold focus:ring-2 focus:ring-blue-500"
                        icon="magnifying-glass" />
                </div>

                <div x-data="{ filtersExpanded: true }"
                    class="bg-zinc-50 dark:bg-black/30 border border-zinc-100 dark:border-zinc-800 rounded-2xl transition-all duration-300">
                    <div class="flex items-center justify-between p-3 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800/50 rounded-2xl transition-colors"
                        @click="filtersExpanded = !filtersExpanded">
                        <div class="flex items-center gap-2">
                            <svg class="size-4 text-zinc-400 transition-transform duration-300"
                                :class="filtersExpanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                            <span
                                class="text-xs font-black uppercase text-zinc-500 tracking-wider select-none">{{ __('words.filters') }}</span>
                        </div>
                        <button x-show="$wire.city_id || $wire.route_from_id || $wire.route_to_id"
                            wire:click.stop="clearAll"
                            class="text-[10px] font-bold text-red-500 hover:underline uppercase tracking-wider">{{ __('words.clear_all') }}</button>
                    </div>
                    <div x-show="filtersExpanded" x-collapse class="px-3 pb-3 space-y-2">
                        <x-wireui-select wire:model.live.debounce.300ms='city_id' :label="__('pages.cities.plural')" :placeholder="__('pages.cities.single')"
                            :async-data="[
                                'api' => route('api.data', ['table' => 'cities', 'locale' => app()->getLocale()]),
                            ]" option-label="name" option-value="id" />
                        <div class="grid grid-cols-1 gap-2">
                            <x-wireui-select wire:model.live.debounce.300ms='route_from_id' :label="__('words.route_from')"
                                :placeholder="__('words.route_from')" :async-data="[
                                    'api' => route('api.data', [
                                        'table' => 'locations',
                                        'locale' => app()->getLocale(),
                                        'city_id' => $city_id,
                                    ]),
                                ]" option-label="name" option-value="id" />
                            <x-wireui-select wire:model.live.debounce.300ms='route_to_id' :label="__('words.route_to')"
                                :placeholder="__('words.route_to')" :async-data="[
                                    'api' => route('api.data', [
                                        'table' => 'locations',
                                        'locale' => app()->getLocale(),
                                        'city_id' => $city_id,
                                    ]),
                                ]" option-label="name" option-value="id" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pb-2 border-b border-zinc-100 dark:border-zinc-800">
                    <span class="text-sm font-bold text-zinc-500">{{ __('pages.bus_lines.plural') }}</span>
                    <span
                        class="bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-black px-2.5 py-1 rounded-lg">{{ $buses_lines->total() }}</span>
                </div>
            </div>

            {{-- RESULTS LIST --}}
            <div class="flex-1 overflow-y-auto px-4 pb-4 space-y-3 soft-scrollbar relative">
                <div wire:loading.flex wire:target="search, city_id, route_from_id, route_to_id"
                    class="absolute inset-0 bg-white/60 dark:bg-zinc-900/60 backdrop-blur-sm z-10 items-center justify-center rounded-2xl">
                    <x-wireui-icon name="arrow-path" class="size-8 text-blue-600 animate-spin" />
                </div>

                @if (!empty($multiRouteSegments))
                    {{-- MULTI-SEGMENT ROUTE --}}
                    <div class="mb-2">
                        <h3 class="text-xs font-black text-blue-500 uppercase tracking-wider mb-3">
                            {{ __('words.suggested_routes') }}</h3>
                        @foreach ($multiRouteSegments as $segment)
                            <div x-on:click="selectRoute({{ Js::from($segment['route_json_url']) }}, {{ Js::from($segment['from_name']) }}, {{ Js::from($segment['to_name']) }}, {{ Js::from($segment['id']) }})"
                                class="group relative w-full p-3 rounded-2xl border transition-all duration-300 cursor-pointer overflow-hidden mb-3"
                                :class="selectedRouteId === {{ $segment['id'] }} ?
                                    'bg-blue-50 dark:bg-blue-900/20 border-blue-500 ring-1 ring-blue-500 shadow-md' :
                                    'bg-white dark:bg-zinc-900/50 border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700 hover:shadow-lg'">

                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center pt-2 shrink-0">
                                        <div
                                            class="size-3 rounded-full border-[3px] border-blue-500 bg-white dark:bg-zinc-900">
                                        </div>
                                        <div
                                            class="w-0.5 grow my-1 bg-gradient-to-b from-blue-500 to-red-500 rounded-full opacity-60">
                                        </div>
                                        <div
                                            class="size-3 rounded-full bg-red-500 border-[3px] border-white dark:border-zinc-900 shadow-sm">
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0 flex flex-col gap-3">
                                        <div>
                                            <span
                                                class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">{{ __('words.from') }}</span>
                                            <h3
                                                class="text-sm font-bold text-zinc-900 dark:text-zinc-100 leading-tight truncate">
                                                {{ $segment['from_name'] }}</h3>
                                        </div>
                                        <div>
                                            <span
                                                class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">{{ __('words.to') }}</span>
                                            <h3
                                                class="text-sm font-bold text-zinc-900 dark:text-zinc-100 leading-tight truncate">
                                                {{ $segment['to_name'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- SINGLE LINES --}}
                    @forelse ($buses_lines as $line)
                        <div x-on:click="selectRoute({{ Js::from($line->route_json_file) }}, {{ Js::from($line->from_location_name) }}, {{ Js::from($line->to_location_name) }}, {{ Js::from($line->id) }})"
                            class="group relative w-full p-3 rounded-2xl border transition-all duration-300 cursor-pointer overflow-hidden"
                            :class="selectedRouteId === {{ $line->id }} ?
                                'bg-blue-50 dark:bg-blue-900/20 border-blue-500 ring-1 ring-blue-500 shadow-md' :
                                'bg-white dark:bg-zinc-900/50 border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700 hover:shadow-lg'">

                            @auth
                                @php $isFavorite = in_array((int) $line->from_location_id, $favorite_line_ids ?? [], true); @endphp
                                <button type="button" wire:click="toggleFavoriteLine({{ $line->from_location_id }})"
                                    @click.stop
                                    class="absolute top-3 ltr:right-3 rtl:left-3 z-5 p-2 rounded-full transition-all duration-200"
                                    :class="{{ $isFavorite ? 'true' : 'false' }} || selectedRouteId === {{ $line->id }} ?
                                        'bg-white dark:bg-zinc-800 shadow-sm' :
                                        'hover:bg-zinc-100 dark:hover:bg-zinc-700'"
                                    title="{{ $isFavorite ? __('words.remove_from_favorites') : __('words.add_to_favorites') }}">
                                    @if ($isFavorite)
                                        <svg class="size-4 text-yellow-500 drop-shadow-sm" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path
                                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                    @else
                                        <svg class="size-4 text-zinc-400 hover:text-red-500 transition-colors"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path
                                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                    @endif
                                </button>
                            @endauth

                            <div class="flex gap-3">
                                <div class="flex flex-col items-center pt-2 shrink-0">
                                    <div
                                        class="size-3 rounded-full border-[3px] border-blue-500 bg-white dark:bg-zinc-900">
                                    </div>
                                    <div
                                        class="w-0.5 grow my-1 bg-gradient-to-b from-blue-500 to-red-500 rounded-full opacity-60">
                                    </div>
                                    <div
                                        class="size-3 rounded-full bg-red-500 border-[3px] border-white dark:border-zinc-900 shadow-sm">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col gap-3">
                                    <div>
                                        <span
                                            class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-0.5">{{ __('words.from') }}</span>
                                        <h3
                                            class="text-sm font-bold text-zinc-900 dark:text-zinc-100 leading-tight truncate ltr:pr-8 rtl:pl-8">
                                            {{ $line->from_location_name }}</h3>
                                    </div>
                                    <div>
                                        <span
                                            class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-0.5">{{ __('words.to') }}</span>
                                        <h3
                                            class="text-sm font-bold text-zinc-900 dark:text-zinc-100 leading-tight truncate ltr:pr-8 rtl:pl-8">
                                            {{ $line->to_location_name }}</h3>
                                    </div>
                                </div>

                                <div class="flex items-center justify-center absolute bottom-4 ltr:right-4 rtl:left-4">
                                    <div class="size-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="selectedRouteId === {{ $line->id }} ?
                                            'bg-blue-600 text-white shadow-lg shadow-blue-500/30' :
                                            'bg-zinc-100 dark:bg-zinc-800 text-zinc-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/40 group-hover:text-blue-600'">
                                        <svg class="size-4 transition-transform duration-300 rtl:rotate-180"
                                            :class="selectedRouteId === {{ $line->id }} ? '' :
                                                'ltr:group-hover:translate-x-0.5 rtl:group-hover:-translate-x-0.5'"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="flex flex-col items-center justify-center h-48 text-center bg-zinc-50 dark:bg-zinc-900/30 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800">
                            <div
                                class="size-14 bg-white dark:bg-zinc-800 rounded-2xl shadow-sm flex items-center justify-center mb-4">
                                <svg class="size-6 text-zinc-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-zinc-500">{{ __('words.no_results') }}</p>
                        </div>
                    @endforelse
                @endif
            </div>

            @if (empty($multiRouteSegments))
                <div class="p-3 border-t border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                    {{ $buses_lines->links('livewire::simple-tailwind') }}
                </div>
            @endif
        </div>

        {{-- MAP AREA --}}
        <div class="flex-1 relative h-full bg-zinc-200 dark:bg-black"
            @location-selected.window="$wire.set('route_from_id', $event.detail.id)">
            <x-ui.map id="public-map" :zoom-level="13" :disable-mark-action="true" :has-multiple="true" :marks="$allLocations" />

            <div class="lg:hidden absolute bottom-8 left-1/2 -translate-x-1/2 z-20" x-cloak x-show="!mobilePanelOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-10 opacity-0 scale-95"
                x-transition:enter-end="translate-y-0 opacity-100 scale-100">
                <button @click="mobilePanelOpen = true"
                    class="h-12 px-6 rounded-2xl bg-blue-600 text-white shadow-xl shadow-blue-600/30 flex items-center gap-3 font-bold ring-4 ring-white/50 dark:ring-zinc-900/50 transform transition-all active:scale-95">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                    <span>{{ __('words.filters') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

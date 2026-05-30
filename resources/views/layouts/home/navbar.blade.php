@php
    $authUser = auth()->user();
    $isAdmin = (bool) ($authUser?->user_type === 'admin');
    $authUserName = (string) ($authUser?->name ?? 'بەکارهێنەر');

    $links = [
        [
            'name' => __('words.home'),
            'route' => 'home',
            'active' => ['home'],
            'icon' => 'home',
        ],
        [
            'name' => __('words.map'),
            'route' => 'map',
            'active' => ['map'],
            'icon' => 'map',
        ],
        [
            'name' => __('words.about'),
            'route' => 'about',
            'active' => ['about'],
            'icon' => 'information-circle',
        ],
        [
            'name' => __('words.contact'),
            'route' => 'contact',
            'active' => ['contact'],
            'icon' => 'phone',
        ],
    ];

    $companyName = setting()->get('company_name', 'Rega');

    $iconClassButton =
        'inline-flex size-9 md:size-10 shrink-0 items-center justify-center rounded-xl border border-zinc-200 bg-white/90 text-zinc-600 shadow-sm transition hover:border-zinc-300 hover:bg-zinc-50 hover:text-zinc-950 active:scale-95 focus:outline-none focus:ring-2 focus:ring-zinc-500/15 dark:border-white/10 dark:bg-zinc-900/80 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-white';
@endphp

<nav x-data="{
    mobileOpen: false,
    searchOpen: false,
    scrolled: false,
    query: '',
    results: [],
    loading: false,
    error: '',
    timer: null,
    token: 0,
    controller: null,
    isDesktop: window.innerWidth >= 1024,
    locale: @js(app()->getLocale()),
    endpoint: @js(route('api.data', ['table' => 'locations'])),
    urlTemplate: @js(route('words.show', ['location' => 'LOCATION_ID'])),
    noResults: @js(__('words.no_results')),
    syncScreen() {
        this.isDesktop = window.innerWidth >= 1024;

        if (this.isDesktop) {
            this.mobileOpen = false;
        }
    },

    openSearch() {
        this.mobileOpen = false;
        this.searchOpen = true;
        this.$nextTick(() => {
            this.$refs.searchInput?.focus();
        });
    },

    closeSearch() {
        this.searchOpen = false;
        this.query = '';
        this.results = [];
        this.loading = false;
        this.error = '';
        clearTimeout(this.timer);
        this.controller?.abort();
    },

    resultUrl(id) {
        return this.urlTemplate.replace('LOCATION_ID', id);
    },

    search() {
        clearTimeout(this.timer);
        this.controller?.abort();
        const keyword = this.query.trim();
        this.error = '';
        if (keyword.length < 2) {
            this.results = [];
            this.loading = false;
            return;
        }

        this.loading = true;
        const currentToken = ++this.token;
        this.timer = setTimeout(async () => {
            this.controller = new AbortController();
            try {
                const url = new URL(this.endpoint, window.location.origin);
                url.searchParams.set('search', keyword);
                url.searchParams.set('locale', this.locale);
                const response = await fetch(url, {
                    headers: {
                        Accept: 'application/json',
                    },
                    signal: this.controller.signal,
                });

                if (!response.ok) {
                    throw new Error('Search failed');
                }
                const data = await response.json();
                if (currentToken !== this.token) {
                    return;
                }
                this.results = Array.isArray(data) ? data.slice(0, 12) : [];
            } catch (e) {
                if (e.name === 'AbortError' || currentToken !== this.token) {
                    return;
                }
                this.results = [];
                this.error = this.noResults;
            } finally {
                if (currentToken === this.token) {
                    this.loading = false;
                }
            }
        }, 250);
    },
}" x-init="$watch('query', () => search());
syncScreen()"
    x-effect="document.body.classList.toggle('overflow-hidden', searchOpen || mobileOpen)"
    @scroll.window="scrolled = window.scrollY > 12" @keydown.window.ctrl.k.prevent="openSearch()"
    @keydown.window.meta.k.prevent="openSearch()" @keydown.window.escape="mobileOpen = false" @resize.window="syncScreen()"
    class="fixed inset-x-0 top-0 z-50 border-b transition duration-300"
    :class="scrolled
        ?
        'border-zinc-200/70 bg-white/85 py-3 shadow-lg shadow-black/5 backdrop-blur-xl dark:border-white/10 dark:bg-zinc-950/80' :
        'border-transparent bg-transparent py-5'">

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-[auto_1fr_auto] items-center gap-2 sm:gap-4">
            {{-- Logo --}}
            <a href="{{ route('home') }}" wire:navigate class="group relative z-50 flex items-center gap-3">
                <img src="{{ getCompanyLogo() }}" alt="{{ $companyName }}"
                    class="h-10 w-auto transition duration-300 group-hover:scale-105">
            </a>

            <nav id="main-navigation" x-cloak x-show="mobileOpen || isDesktop"
                x-transition:enter="transition ease-out duration-200 lg:transition-none"
                x-transition:enter-start="-translate-y-3 opacity-0 scale-95 lg:translate-y-0 lg:opacity-100 lg:scale-100"
                x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150 lg:transition-none"
                x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                x-transition:leave-end="-translate-y-3 opacity-0 scale-95 lg:translate-y-0 lg:opacity-100 lg:scale-100"
                @click.outside="if (! isDesktop) mobileOpen = false"
                class="absolute inset-x-4 top-full z-40 mt-3 rounded-3xl border border-zinc-200 p-2 shadow-2xl shadow-black/10 dark:border-white/10 lg:static lg:inset-auto lg:z-auto lg:mt-0 lg:flex lg:w-full lg:min-w-0 lg:items-center lg:justify-center lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none"
                :class="mobileOpen ? 'bg-white/95 dark:bg-zinc-900/90 backdrop-blur-sm' : 'bg-transparent'">
                <div
                    class="grid grid-cols-1 gap-2 lg:inline-flex lg:items-center lg:gap-1 lg:rounded-full lg:border lg:border-zinc-200/70 lg:bg-white/70 lg:p-1.5 lg:shadow-sm lg:backdrop-blur-xl dark:lg:border-white/10 dark:lg:bg-zinc-900/70">
                    @foreach ($links as $link)
                        @php
                            $linkRoute = $link['route'];
                            $isActive = request()->routeIs($link['active'] ?? [$linkRoute]);
                        @endphp

                        <a href="{{ route($linkRoute) }}" wire:navigate @click="mobileOpen = false"
                            @class([
                                'group flex items-center justify-between gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition lg:justify-center lg:rounded-full lg:px-5 lg:py-2',
                                'bg-zinc-950 text-white shadow-sm dark:bg-white dark:text-zinc-950' => $isActive,
                                'text-zinc-700 hover:bg-zinc-100 hover:text-zinc-950 dark:text-zinc-300 dark:hover:bg-white/10 dark:hover:text-white' => !$isActive,
                            ]) aria-current="{{ $isActive ? 'page' : 'false' }}">

                            <span class="flex min-w-0 items-center gap-3">
                                <span @class([
                                    'grid size-10 shrink-0 place-items-center rounded-2xl transition lg:hidden',
                                    'bg-white/15 text-white dark:bg-zinc-950/10 dark:text-zinc-950' => $isActive,
                                    'bg-zinc-100 text-zinc-500 dark:bg-white/10 dark:text-zinc-300' => !$isActive,
                                ])>
                                    <x-wireui-icon :name="$link['icon']" class="size-5" />
                                </span>

                                <span class="truncate">{{ $link['name'] }}</span>
                            </span>

                            <x-wireui-icon name="arrow-right"
                                class="size-4 shrink-0 opacity-40 transition group-hover:translate-x-1 rtl:rotate-180 rtl:group-hover:-translate-x-1 lg:hidden" />
                        </a>
                    @endforeach
                </div>
            </nav>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-1.5 sm:gap-2">
                <button type="button" class="{{ $iconClassButton }}" @click="openSearch()"
                    aria-label="{{ __('words.search') }}">
                    <x-wireui-icon name="magnifying-glass" class="size-5" />
                </button>
                <x-switch-language size="size-9 md:size-10" />
                <x-switch-theme size="size-9 md:size-10" />

                @auth
                    <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                        <button type="button" class="{{ $iconClassButton }}" @click="open = ! open"
                            aria-label="{{ __('words.user_menu') }}">
                            <span
                                class="grid size-8 place-items-center rounded-full bg-zinc-100 text-sm font-black text-zinc-800 dark:bg-white/10 dark:text-white">
                                {{ str($authUserName)->substr(0, 1)->upper() }}
                            </span>
                        </button>

                        <div x-cloak x-show="open" @click.outside="open = false" x-transition.origin.top.right
                            class="absolute top-full z-50 mt-3 w-72 max-w-[calc(100vw-1rem)] overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-xl shadow-black/10 ltr:right-0 rtl:left-0 dark:border-white/10 dark:bg-zinc-900">
                            <div class="border-b border-zinc-100 p-4 dark:border-white/10">
                                <p class="truncate font-bold text-zinc-950 dark:text-white">
                                    {{ $authUserName }}
                                </p>

                                <p class="truncate text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $authUser?->email }}
                                </p>
                            </div>

                            <div class="space-y-1 p-2">
                                @if ($isAdmin)
                                    <a href="{{ route('dashboard.home') }}" wire:navigate.hover
                                        class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-950 dark:text-zinc-300 dark:hover:bg-white/10 dark:hover:text-white">
                                        <x-wireui-icon name="bars-4" class="size-5" />
                                        @lang('words.dashboard')
                                    </a>
                                @endif

                                <a href="{{ route('favorites') }}" wire:navigate
                                    class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-950 dark:text-zinc-300 dark:hover:bg-white/10 dark:hover:text-white">
                                    <x-wireui-icon name="heart" class="size-5 fill-current text-red-500" />
                                    @lang('words.favorites')
                                </a>
                                <a href="{{ route('logout') }}"
                                    class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:hover:bg-red-950/30">
                                    <x-wireui-icon name="arrow-left-on-rectangle" class="size-5" />
                                    @lang('words.logout')
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" wire:navigate class="{{ $iconClassButton }} lg:hidden"
                        aria-label="{{ __('words.login') }}">
                        <x-wireui-icon name="user" class="size-5" />
                    </a>

                    <a href="{{ route('login') }}" wire:navigate
                        class="hidden h-10 items-center gap-2 rounded-xl bg-zinc-950 px-4 text-sm font-bold text-white shadow-sm transition hover:bg-zinc-800 active:scale-95 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-200 lg:inline-flex">
                        {{ __('words.get_started') }}
                        <x-wireui-icon name="arrow-right" class="size-4 rtl:rotate-180" />
                    </a>
                @endauth

                <button type="button" class="{{ $iconClassButton }} lg:hidden" @click.stop="mobileOpen = ! mobileOpen"
                    aria-label="menu" aria-controls="main-navigation" :aria-expanded="mobileOpen.toString()">

                    <x-wireui-icon name="bars-3" class="size-5" />
                </button>
            </div>
        </div>
    </div>

    {{-- Search modal --}}
    <template x-teleport="body">
        <div x-cloak x-show="searchOpen" @keydown.escape.window="closeSearch()"
            class="fixed inset-0 z-50 flex items-start justify-center px-4 pt-20 sm:pt-28">
            <div class="absolute inset-0 bg-zinc-950/70 backdrop-blur-xl" @click="closeSearch()"></div>

            <section x-show="searchOpen" x-transition.origin.top @click.stop
                class="relative w-full max-w-2xl overflow-hidden rounded-3xl border border-white/20 bg-white shadow-2xl shadow-black/20 dark:border-white/10 dark:bg-zinc-950">
                <div class="flex items-center gap-3 border-b border-zinc-100 p-4 dark:border-white/10 sm:p-5">
                    <x-wireui-icon name="magnifying-glass" class="size-5 shrink-0 text-zinc-400" />

                    <input x-ref="searchInput" x-model="query" type="text" autocomplete="off"
                        placeholder="{{ __('words.search', ['attr' => __('pages.locations.single')]) }}"
                        class="min-w-0 flex-1 border-0 bg-transparent p-0 text-base font-bold text-zinc-950 outline-none placeholder:text-zinc-400 focus:ring-0 dark:text-white sm:text-lg">

                    <button type="button"
                        class="rounded-xl p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-700 dark:hover:bg-white/10 dark:hover:text-white"
                        @click="closeSearch()">
                        <x-wireui-icon name="x-mark" class="size-5" />
                    </button>
                </div>

                <div class="max-h-[60vh] overflow-y-auto p-2">
                    <div x-show="loading" class="flex justify-center py-10">
                        <span
                            class="size-6 animate-spin rounded-full border-2 border-zinc-200 border-t-zinc-950 dark:border-white/10 dark:border-t-white"></span>
                    </div>

                    <template x-if="!loading && query.trim().length < 2">
                        <div class="px-5 py-10 text-center">
                            <div
                                class="mx-auto mb-3 grid size-12 place-items-center rounded-2xl bg-zinc-100 text-zinc-400 dark:bg-white/10">
                                <x-wireui-icon name="magnifying-glass" class="size-6" />
                            </div>

                            <p class="text-sm font-semibold text-zinc-500">
                                {{ __('words.search', ['attr' => __('pages.locations.single')]) }}
                            </p>
                        </div>
                    </template>

                    <template x-if="!loading && query.trim().length >= 2 && results.length === 0">
                        <div class="px-5 py-10 text-center">
                            <p class="text-sm font-semibold text-zinc-500" x-text="error || noResults"></p>
                        </div>
                    </template>

                    <div x-show="!loading && results.length" class="space-y-1">
                        <template x-for="location in results" :key="location.id">
                            <a :href="resultUrl(location.id)" wire:navigate @click="closeSearch()"
                                class="group flex items-center justify-between gap-4 rounded-2xl px-4 py-3 text-zinc-800 transition hover:bg-zinc-100 hover:text-zinc-950 dark:text-zinc-100 dark:hover:bg-white/10">
                                <span class="flex min-w-0 items-center gap-3">
                                    <span
                                        class="grid size-10 shrink-0 place-items-center rounded-2xl bg-zinc-100 text-zinc-400 transition group-hover:bg-white dark:bg-white/10">
                                        <x-wireui-icon name="map-pin" class="size-5" />
                                    </span>

                                    <span class="truncate font-bold" x-text="location.name || '-'"></span>
                                </span>

                                <x-wireui-icon name="arrow-right"
                                    class="size-4 shrink-0 opacity-40 transition group-hover:translate-x-1 rtl:rotate-180 rtl:group-hover:-translate-x-1" />
                            </a>
                        </template>
                    </div>
                </div>
            </section>
        </div>
    </template>
</nav>

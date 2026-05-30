<nav
    class="print:hidden w-full bg-white/85 dark:bg-zinc-950/70 text-zinc-900 dark:text-zinc-100 sticky top-0 left-0 z-40 px-4 py-3 backdrop-blur-xl backdrop-filter backdrop-saturate-150 mb-3 border border-zinc-200/70 dark:border-zinc-800/60 shadow-sm">
    <div class="flex items-center justify-between w-full gap-4">
        <div class="flex-1 max-w-xs visible lg:invisible">
            <a href="{{ route('dashboard.home') }}" wire:navigate.hover class="flex items-center gap-3 group">
                <img src="{{ getCompanyLogo() }}" alt="{{ setting()->get('company_name', config('app.name')) }} Logo"
                    loading="lazy"
                    class="object-cover rounded-xl size-10 border border-zinc-200 dark:border-zinc-800 transition-transform duration-300 group-hover:scale-105 flex-shrink-0">
                <h1
                    class="hidden lg:block text-sm font-medium text-zinc-900 dark:text-white group-hover:text-zinc-600 dark:group-hover:text-zinc-400 transition-colors duration-300 line-clamp-2 leading-tight">
                    {{ setting()->get('company_name', config('app.name')) }}
                </h1>
            </a>
        </div>
        <div
            class="flex gap-1.5 justify-center items-center flex-row rtl:items-stretch rtl:flex-row-reverse lg:rtl:flex-row">
            <div class="relative">
                <x-switch-language class="hover:scale-105 transition-transform duration-200" />
            </div>
            <div class="relative">
                <x-switch-theme class="hover:scale-105 transition-transform duration-200" />
            </div>
            <div class="relative" x-data="{ open: false }">
                <button type="button" name="user-menu" aria-label="{{ __('words.user_menu') }}"
                    x-on:click="open = !open"
                    class="group relative shrink-0 size-10 border text-zinc-600 dark:text-zinc-300 border-zinc-200 dark:border-zinc-700 rounded-xl inline-flex items-center justify-center bg-white/90 dark:bg-zinc-800/80 hover:border-zinc-300 dark:hover:border-zinc-600 hover:text-zinc-600 dark:hover:text-zinc-400 transition-all duration-300 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-zinc-500/15 dark:focus:ring-zinc-600/20">
                    <div
                        class="flex items-center justify-center object-cover rounded-full size-8 group-hover:scale-105 transition-transform duration-300">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </button>
                <div class="absolute z-50 mt-3 w-64 overflow-hidden bg-white/95 rounded-xl shadow-lg border border-zinc-200/80 dark:border-zinc-700 top-full ltr:right-0 rtl:left-0 dark:bg-zinc-800/95"
                    x-cloak x-show="open" x-collapse x-on:click.away="open = false"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    <div class="p-2 md:p-3 border-b border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex items-center justify-center object-cover rounded-full size-8 group-hover:scale-105 transition-transform duration-300">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-zinc-900 dark:text-white truncate">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400 truncate">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 md:p-3">
                        <a href="{{ route('logout') }}"
                            class="flex items-center gap-3 px-2 py-1.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200 group">
                            <svg class="size-5 text-red-500 group-hover:text-red-600 transition-colors duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            @lang('words.logout')
                        </a>
                    </div>
                </div>
            </div>
            <div class="lg:hidden relative">
                <button type="button" name="sidebar"
                    class="flex items-center justify-center size-10 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white/90 dark:bg-zinc-800/85 text-zinc-600 dark:text-zinc-300 hover:border-zinc-300 dark:hover:border-zinc-600 hover:text-zinc-600 dark:hover:text-zinc-400 transition-all duration-300"
                    x-on:click="sidebarOpen = !sidebarOpen">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                        class="size-5 transition-transform duration-300" x-show="!sidebarOpen"
                        :class="{ 'rotate-180': $store.global && $store.global.direction === 'rtl' }">
                        <path d="M4 5L16 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M4 12L20 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M4 19L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                        class="size-5 transition-transform duration-300" x-show="sidebarOpen">
                        <path d="M6 18L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

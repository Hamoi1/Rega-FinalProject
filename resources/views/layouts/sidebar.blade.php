@php
    $user_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5.5">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
    </svg>';
@endphp
<aside
    class="print:hidden fixed top-[4.4rem] lg:top-0 rtl:right-0 ltr:left-0 w-full lg:w-[16.5rem] 3xl:w-[18rem] min-h-dvh overflow-y-hidden p-1.5 py-2 z-[31] lg:-translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out bg-white/90 text-zinc-700 dark:text-zinc-200 dark:bg-zinc-950/85 border-r border-zinc-200/70 dark:border-zinc-800/60 backdrop-blur-sm"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
    <div class="flex flex-col w-full pb-32 overflow-y-auto gap-y-3 child:w-full hide-scrollbar max-h-dvh grow p-1.5">
        {{-- user profile --}}
        <div
            class="bg-white/95 p-2 rounded-xl flex items-center gap-2 w-full border border-zinc-200/80 dark:bg-zinc-900/80 dark:border-zinc-800">
            <div class="shrink-0">
                <div
                    class="flex items-center justify-center object-cover rounded-full size-8 group-hover:scale-105 transition-transform duration-300">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
            <div class="flex-1 grow min-w-0 truncate">
                <p class="font-medium text-zinc-900 dark:text-white">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-zinc-600 dark:text-zinc-400">
                    {{ auth()->user()->email }}
                </p>
            </div>
            {{-- logout button --}}
            <div class="ms-auto shrink-0">
                <a href="{{ route('logout') }}"
                    class="flex items-center justify-center size-9 rounded-lg border border-transparent hover:border-red-200 dark:hover:border-red-900/40 hover:-translate-x-1 transition-transform duration-200 text-red-600 dark:text-red-400 bg-red-50/40 dark:bg-red-900/10"
                    title="@lang('words.logout')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                    </svg>
                </a>
            </div>
        </div>
        <!-- Dashboard link -->
        <div class="space-y-4">
            <x-sidebar.link :url="route('dashboard.home')"
                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <rect x="3" y="3" width="7" height="9"/>
                        <rect x="14" y="3" width="7" height="5"/>
                        <rect x="14" y="12" width="7" height="9"/>
                        <rect x="3" y="16" width="7" height="5"/>
                    </svg>'
                :title="__('pages.dashboard.single')" route_name="dashboard.home" />
            <x-sidebar.link :url="route('home')"
                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>'
                :title="__('words.home')" route_name="home" />
        </div>
        <div class="mt-3">
            <h3 class="px-3 mb-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                {{ __('pages.sidebar.user_management') }}
            </h3>
            <div class="space-y-1">
                <x-sidebar.link :url="route('dashboard.users', ['type' => 'admin'])" :icon="$user_icon" :title="__('pages.users.admins')" route_name="dashboard.users" />
            </div>
            <div class="space-y-1">
                <x-sidebar.link :url="route('dashboard.users', ['type' => 'user'])" :icon="$user_icon" :title="__('pages.users.list')" route_name="dashboard.users" />
            </div>
        </div>
        <div class="mt-3">
            <h3 class="px-3 mb-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                {{ __('pages.sidebar.location_management') }}
            </h3>
            <div class="space-y-1">
                <x-sidebar.link :url="route('dashboard.cities')"
                    icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <path d="M3 21h18"/>
                        <path d="M5 21V7l8-4v18"/>
                        <path d="M19 21V11l-6-4"/>
                        <path d="M9 9v.01"/>
                        <path d="M9 12v.01"/>
                        <path d="M9 15v.01"/>
                        <path d="M9 18v.01"/>
                    </svg>'
                    :title="__('pages.cities.list')" route_name='dashboard.cities' />
                <x-sidebar.link :url="route('dashboard.locations')"
                    icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>'
                    :title="__('pages.locations.list')" route_name='dashboard.locations' />
                <x-sidebar.link :url="route('dashboard.bus-lines')"
                    icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <path d="M8 6v6"/><path d="M16 6v6"/>
                        <path d="M2 9h2"/><path d="M20 9h2"/>
                        <rect x="4" y="4" width="16" height="12" rx="2"/>
                        <path d="M8 16v2"/><path d="M16 16v2"/>
                        <circle cx="8" cy="12" r="1" fill="currentColor"/>
                        <circle cx="16" cy="12" r="1" fill="currentColor"/>
                    </svg>'
                    :title="__('pages.bus_lines.list')" route_name='dashboard.bus-lines' />
            </div>
        </div>

        <div class="mt-3">
            <h3 class="px-3 mb-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                {{ __('pages.sidebar.content_management') }}
            </h3>
            <div class="space-y-1">
                <x-sidebar.link :url="route('dashboard.faqs')"
                    icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>
                    </svg>'
                    :title="__('pages.faqs.list')" route_name='dashboard.faqs' />
            </div>
        </div>
    </div>
</aside>

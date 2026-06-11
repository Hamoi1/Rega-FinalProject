<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(app()->getLocale(), ['ar', 'ckb']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if (isset($title))
            {{ $title . ' | ' }}
        @endif
        {{ setting()->get('company_name') }}
    </title>
    {!! SEO::generate() !!}
    <meta name="theme-color" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <link rel="shortcut icon" href="{{ getCompanyLogo() }}" type="image/png, image/x-wireui-icon, image/webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Theme Script --}}
    <script>
        const initTheme = () => {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = theme === 'dark' || (!theme && prefersDark);

            document.documentElement.classList.toggle('dark', isDark);

            const metaTheme = document.querySelector('meta[name="theme-color"]');
            if (metaTheme) {
                metaTheme.setAttribute('content', isDark ? '#18181b' : '#ffffff');
            }
        };

        (function() {
            initTheme();
        })();
    </script>

    <script>
        window.Rega = window.Rega || {};
        window.Rega.locale = @js(app()->getLocale());
        window.Rega.i18n = Object.assign(window.Rega.i18n || {}, @js([
    'map.searchLabel' => __('words.search_label'),
    'map.notFoundMessage' => __('words.not_found_message'),
    'map.searchResult' => __('words.search_result'),
    'map.unknownLocation' => __('words.unknown_location'),
    'map.unknown' => __('words.unknown'),
    'map.selectedLocation' => __('words.selected_location'),
    'map.coordinates' => __('words.coordinates'),
    'map.postcode' => __('words.postcode'),
    'map.removeMarker' => __('words.remove_marker'),
    'map.location' => __('words.location'),
    'map.syncingData' => __('words.syncing_data'),
    'map.styles.standard' => __('words.styles.standard'),
    'map.styles.streets' => __('words.styles.streets'),
    'map.styles.vivid' => __('words.styles.vivid'),
    'map.styles.satellite' => __('words.styles.satellite'),
    'map.styles.terrain' => __('words.styles.terrain'),
    'words.zoomIn' => __('words.zoom_in'),
    'words.zoomOut' => __('words.zoom_out'),
    'words.locateMe' => __('words.locate_me'),
    'words.toggleFullscreen' => __('words.toggle_fullscreen'),
    'words.searchLocation' => __('words.search_location'),
]));
    </script>
    @echo(GetCssFiles())
    @livewireStyles
</head>

<body
    class="antialiased bg-white/95 dark:bg-zinc-950/95 text-zinc-900 dark:text-zinc-100 p-0 m-0 print:h-auto print:bg-white"
    x-data="{ sidebarOpen: false }" x-init="initTheme();">

    @persist('notification')
        <x-wireui-notifications z-index="z-[999999999]" position="top-right" />
    @endpersist

    @include('layouts.loader')
    <livewire:offline-state />

    @if (request()->routeIs('dashboard.*'))
        @auth
            @include('layouts.sidebar')

            <main
                class="h-dvh overflow-auto hide-scrollbar transition-all duration-300 ease-in-out lg:w-[calc(100%-17rem)] 3xl:w-[calc(100%-18.5rem)] rtl:lg:mr-[17rem] ltr:lg:ml-[17rem] rtl:3xl:mr-[18.5rem] ltr:3xl:ml-[18.5rem] w-full print:shadow-none print:p-0 print:m-0 print:w-full print:max-w-none print:min-h-0 bg-white dark:bg-zinc-950/50 text-zinc-900 dark:text-zinc-100 rounded-lg">
                @include('layouts.navbar')
                <div class="p-1 lg:p-2">
                    {{ $slot }}
                </div>
            </main>

        @endauth
    @elseif(request()->routeIs('register') || request()->routeIs('login') || request()->routeIs('map'))
        {{ $slot }}
    @else
        @include('layouts.home.navbar')
        <main class="min-h-screen">
            {{ $slot }}
        </main>
        @include('layouts.home.footer')
    @endif

    @livewireScripts
    @wireUiScripts
    @echo(GetJsFiles())

</body>

</html>

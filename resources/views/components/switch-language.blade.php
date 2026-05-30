@props([
    'size' => 'size-10',
])
<div class="relative" x-data="{ open: false }">
    <button type="button"
        class="group inline-flex {{ $size }} shrink-0 items-center justify-center rounded-xl border border-zinc-200 bg-white/90 text-zinc-600 shadow-sm transition hover:border-zinc-300 hover:bg-zinc-50 hover:text-zinc-950 active:scale-95 focus:outline-none focus:ring-2 focus:ring-zinc-500/15 dark:border-white/10 dark:bg-zinc-900/80 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-white"
        name="change_language" x-on:click="open = !open" x-ref="changeLanguageButton">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="size-5 transition-transform duration-200 group-hover:scale-110 group-hover:rotate-3">
            <path
                d="M2 12C2 13.0519 2.18046 14.0617 2.51212 15M13.0137 9H21.5015M11 15H2.51212M21.5015 9C20.266 5.50442 16.9323 3 13.0137 3C14.6146 3 15.9226 6.76201 16.0091 11.5M21.5015 9C21.7803 9.78867 21.9522 10.6278 22 11.5M2.51212 15C3.74763 18.4956 7.08134 21 11 21C9.45582 21 8.18412 17.5 8.01831 13"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path
                d="M2 5.29734C2 4.19897 2 3.64979 2.18738 3.22389C2.3861 2.77223 2.72861 2.40921 3.15476 2.1986C3.55661 2 4.07478 2 5.11111 2H6C7.88562 2 8.82843 2 9.41421 2.62085C10 3.2417 10 4.24095 10 6.23944V8.49851C10 9.37026 10 9.80613 9.73593 9.95592C9.47186 10.1057 9.12967 9.86392 8.4453 9.38036L8.34103 9.30669C7.84086 8.95329 7.59078 8.77658 7.30735 8.68563C7.02392 8.59468 6.72336 8.59468 6.12223 8.59468H5.11111C4.07478 8.59468 3.55661 8.59468 3.15476 8.39608C2.72861 8.18547 2.3861 7.82245 2.18738 7.37079C2 6.94489 2 6.39571 2 5.29734Z"
                stroke="currentColor" stroke-width="1.5" />
            <path
                d="M22 17.2973C22 16.199 22 15.6498 21.8126 15.2239C21.6139 14.7722 21.2714 14.4092 20.8452 14.1986C20.4434 14 19.9252 14 18.8889 14H18C16.1144 14 15.1716 14 14.5858 14.6209C14 15.2417 14 16.2409 14 18.2394V20.4985C14 21.3703 14 21.8061 14.2641 21.9559C14.5281 22.1057 14.8703 21.8639 15.5547 21.3804L15.659 21.3067C16.1591 20.9533 16.4092 20.7766 16.6926 20.6856C16.9761 20.5947 17.2766 20.5947 17.8778 20.5947H18.8889C19.9252 20.5947 20.4434 20.5947 20.8452 20.3961C21.2714 20.1855 21.6139 19.8225 21.8126 19.3708C22 18.9449 22 18.3957 22 17.2973Z"
                stroke="currentColor" stroke-width="1.5" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div class="z-10000! mt-3 w-56 overflow-hidden bg-white/95 dark:bg-zinc-900/90 rounded-xl shadow-lg border border-zinc-200/80 dark:border-zinc-700/80 backdrop-blur-sm"
        x-show="open" x-anchor.bottom-end.offset.4="$refs.changeLanguageButton" x-on:click.away="open = false" x-cloak
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <!-- Language Options -->
        <div class="py-2">
            @php
                $languages = [
                    'en' => [
                        'name' => __('words.languages.en'),
                        'flag' => '🇺🇸',
                        'native' => 'English',
                    ],
                    'ckb' => [
                        'name' => __('words.languages.ckb'),
                        'flag' => '🏴',
                        'native' => 'کوردی',
                    ],
                    'ar' => [
                        'name' => __('words.languages.ar'),
                        'flag' => '🇸🇦',
                        'native' => 'العربية',
                    ],
                ];
            @endphp

            @foreach ($languages as $lang => $data)
                <a href="{{ route('change-language', $lang) }}"
                    class="flex items-center justify-between px-4 py-2.5 text-sm transition {{ app()->getLocale() === $lang ? 'text-zinc-900 dark:text-white font-semibold' : 'text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white' }}">
                    <div class="leading-tight">
                        <div>{{ $data['name'] }}</div>
                        <div class="text-xs text-zinc-400 dark:text-zinc-500">
                            {{ $data['native'] }}
                        </div>
                    </div>
                    @if (app()->getLocale() === $lang)
                        <svg class="w-4 h-4 text-zinc-700 dark:text-zinc-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

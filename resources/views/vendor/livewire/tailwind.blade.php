@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div class="pb-6">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex justify-between flex-1 md:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span
                            class="cursor-not-allowed pointer-events-none min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex jusify-center items-center gap-x-2 text-sm rounded-lg text-zinc-400 dark:text-zinc-500 bg-zinc-100/70 dark:bg-zinc-900/70">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            name="previous-page"
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex jusify-center items-center gap-x-2 text-sm rounded-lg text-zinc-800 dark:text-zinc-100 bg-white/90 dark:bg-zinc-900/80 border border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-800/70 transition-colors duration-200">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                </span>
                <span>
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            name="next-page"
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex jusify-center items-center gap-x-2 text-sm rounded-lg text-zinc-800 dark:text-zinc-100 bg-white/90 dark:bg-zinc-900/80 border border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-800/70 transition-colors duration-200">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span
                            class="cursor-not-allowed pointer-events-none min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex jusify-center items-center gap-x-2 text-sm rounded-lg text-zinc-400 dark:text-zinc-500 bg-zinc-100/70 dark:bg-zinc-900/70">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>
            <div class="hidden md:flex-1 md:flex justify-between flex-wrap items-center gap-3">
                <span class="relative z-0 inline-flex rounded-md  not-reverse gap-2">
                    <span>
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                <span
                                    class="cursor-not-allowed pointer-events-none min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg border border-transparent text-main-800 hover:bg-main-50 focus:outline-none focus:bg-main-50 disabled:opacity-50 disabled:pointer-events-none dark:border-transparent dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                                    aria-hidden="true">
                                    <svg class="w-5 h-5 rtl:transform rtl:rotate-180 rtl:scale-x-100 rtl:scale-y-100"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @else
                            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                name="previous-page"
                                dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                rel="prev"
                                class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 text-zinc-800 dark:text-zinc-100 bg-white/90 dark:bg-zinc-900/80 hover:border-zinc-300 dark:hover:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-800/70 transition-colors duration-200"
                                aria-label="{{ __('pagination.previous') }}">
                                <svg class="w-5 h-5 rtl:transform rtl:rotate-180 rtl:scale-x-100 rtl:scale-y-100"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif
                    </span>

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span
                                    class="brelative inline-flex items-center px-3 py-1.5 -ml-px text-sm font-medium text-main-500 dark:border-main-900 dark:text-white cursor-not-allowed leading-5 select-none rounded-md">{{ $element }}</span>
                            </span>
                        @endif
                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page">
                                            <span
                                                class="cursor-not-allowed pointer-events-none min-h-[38px] min-w-[38px] flex justify-center items-center border border-info-300/60 dark:border-info-600/60 text-info-800 dark:text-info-100 bg-info-50/70 dark:bg-info-900/20 py-2 px-3 text-sm rounded-lg">{{ $page }}</span>
                                        </span>
                                    @else
                                        <button type="button"
                                        name="goto-page-{{ $page }}"
                                            wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                                class="min-h-[38px] min-w-[38px] flex justify-center items-center border border-transparent text-zinc-700 dark:text-zinc-200 hover:text-zinc-900 dark:hover:text-white hover:border-zinc-300 dark:hover:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-800/70 py-2 px-3 text-sm rounded-lg transition-colors duration-200"
                                            aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </button>
                                    @endif
                                </span>
                            @endforeach
                        @endif
                    @endforeach

                    <span>
                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                name="next-page"
                                dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                rel="next"
                                class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 text-zinc-800 dark:text-zinc-100 bg-white/90 dark:bg-zinc-900/80 hover:border-zinc-300 dark:hover:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-800/70 transition-colors duration-200"
                                aria-label="{{ __('pagination.next') }}">
                                <svg class="w-5 h-5 rtl:transform rtl:scale-x-100 rtl:scale-y-100 rtl:rotate-180"fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        @else
                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                <span
                                    class="cursor-not-allowed pointer-events-none min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg border border-transparent text-main-800 hover:bg-main-50 focus:outline-none focus:bg-main-50 disabled:opacity-50 disabled:pointer-events-none dark:border-transparent dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                                    aria-hidden="true">
                                    <svg class="w-5 h-5 rtl:transform rtl:scale-x-100 rtl:scale-y-100 rtl:rotate-180"fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @endif
                    </span>
                </span>
                <p class="">
                    {{ __('words.showing_count_of_table', [
                        'from' => $paginator->firstItem(),
                        'to' => $paginator->lastItem(),
                        'total' => $paginator->total(),
                    ]) }}
                </p>
            </div>
        </nav>
    @endif
</div>

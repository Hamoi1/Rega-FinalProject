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

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex justify-between flex-1">
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
        </nav>
    @endif
</div>

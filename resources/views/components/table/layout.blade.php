<div
    class="overflow-x-auto no-scrollbar rounded-xl dark:shadow print:rounded-none print:shadow-none print:overflow-visible print:p-0 border border-zinc-200 dark:border-zinc-800/80">
    <table {{ $attributes->merge(['class' => 'min-w-full w-full table-auto custom-table print:p-0']) }}>
        @if (isset($header))
            <thead>
                <tr>
                    {{ $header }}
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $body }}
        </tbody>
        @if (isset($footer))
            <tfoot>
                {{ $footer }}
            </tfoot>
        @endif
    </table>
</div>
@if (isset($links) && $links->isNotEmpty())
    <div class="mt-4 pt-3 text-sm text-gray-600 dark:text-gray-400 print:hidden">
        {{ $links }}
    </div>
@endif

@props([
    'icon' => null,
    'label' => null,
])

<div x-data="{ show: false }" x-init="show = $el.querySelector('.dropdown-links .active') !== null;" class="relative w-full">
    <button type="button" x-on:click="show = !show;" name="dropdown-button-{{ $label }}"
        class="relative flex w-full items-center justify-between gap-3 rounded-xl border border-transparent px-3 py-2.5 text-zinc-700 dark:text-zinc-100 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/60 hover:border-zinc-200/60 dark:hover:border-zinc-700/70 transition-all duration-200"
        x-bind:class="{ 'bg-info-50/85 dark:bg-info-900/20 border-info-200/70 dark:border-info-700/80 text-info-800 dark:text-info-100 shadow-[inset_0_0_0_1px_rgba(14,165,233,0.05)]': show }">
        @if (Str::contains($icon, 'svg'))
            <span class="flex size-5 shrink-0 items-center justify-center text-zinc-500 dark:text-zinc-400"
                x-bind:class="{ 'text-info-700 dark:text-info-200': show }">
                {!! $icon !!}
            </span>
        @elseif (isset($icon))
            <x-wireui-icon name="{{ $icon }}" class="size-5 shrink-0 text-zinc-500 dark:text-zinc-400"
                x-bind:class="{ 'text-info-700 dark:text-info-200': show }" />
        @endif
        <span class="flex-1 truncate text-sm font-semibold tracking-tight">{{ $label }}</span>
        <span class="ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="size-5 shrink-0 text-zinc-500 dark:text-zinc-400 transition-transform duration-300 ease-in-out"
                x-bind:class="{ 'transform rotate-180': show, 'transform rotate-0': !show }">
                <path d="M18 9.00005C18 9.00005 13.5811 15 12 15C10.4188 15 6 9 6 9" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </button>
    <div x-cloak x-show="show" x-collapse
        class="dropdown-links relative w-full space-y-1.5 py-2 ltr:pl-7 rtl:pr-7 md:space-y-2">
        <div class="pointer-events-none absolute -top-1.5 w-0.5 rounded bg-info-400/70 transition-all duration-300 ease-in-out ltr:left-4 rtl:right-4"
            x-bind:class="{ 'h-full': show, 'h-0': !show }"></div>
        {{ $slot }}
    </div>
</div>

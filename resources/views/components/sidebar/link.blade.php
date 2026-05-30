@props([
    'url' => '',
    'icon' => '',
    'title' => '',
    'route_name' => '',
    'navigation' => true,
    'target' => null,
])
<a class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent text-zinc-700 dark:text-zinc-100 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/60 hover:border-zinc-200/60 dark:hover:border-zinc-700/70 transition-all duration-200"
    wire:current.exact="bg-info-50/85 dark:bg-info-900/20 text-info-800 dark:text-info-50 border-info-200/70 dark:border-info-700/80 active shadow-[inset_0_0_0_1px_rgba(14,165,233,0.05)]"
    href="{{ $url }}" {{ $attributes }} {{ when($navigation, 'wire:navigate.hover') }}
    target="{{ $target }}">
    <span
        class="absolute inset-y-2 ltr:left-1 rtl:right-1 w-1 rounded-full bg-info-500 opacity-0 transition-all duration-200 group-[.active]:opacity-100"></span>
    @if ($icon || Str::contains($icon, 'svg'))
        <div
            class="flex size-5 shrink-0 items-center justify-center transition-all duration-200 text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-700 dark:group-hover:text-zinc-100 group-[.active]:text-info-700 dark:group-[.active]:text-info-200 group-[.active]:scale-105">
            @if (Str::contains($icon, 'svg'))
                <div class="size-5 group-hover:text-info-700 dark:group-hover:text-info-100"
                    wire:current.exact="text-info-700 dark:text-info-300">
                    {!! $icon !!}
                </div>
            @else
                <x-wireui-icon name="{{ $icon }}"
                    class="size-5 group-hover:text-info-700 dark:group-hover:text-info-100"
                    wire:current.exact="text-info-700 dark:text-info-300" />
            @endif
        </div>
    @endif
    <span
        class="flex-1 truncate text-sm font-semibold tracking-tight text-zinc-700 dark:text-zinc-100 group-hover:text-zinc-900 dark:group-hover:text-white group-[.active]:text-info-800 dark:group-[.active]:text-info-50">
        {{ $title }}
    </span>
    <div class="size-1.5 rounded-full bg-info-600 dark:bg-info-300 opacity-0 transition-all duration-200 group-hover:opacity-70"
        x-bind:class="{ 'opacity-100': $el.parentElement.classList.contains('active') }"></div>
</a>

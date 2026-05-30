@props([
    'placement' => 'bottom-left',
    'title' => __('words.actions'),
    'name' => 'dropdown',
    'icon' => '<svg class="shrink-0 size-5 rotate-90" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <circle cx="12" cy="12" r="1" />
            <circle cx="19" cy="12" r="1" />
            <circle cx="5" cy="12" r="1" />
        </svg>',
    'minWidth' => 'min-w-56',
    'maxWidth' => 'max-w-72',
    'backgroundClass' => 'hover:bg-zinc-100 dark:hover:bg-neutral-700 focus:bg-zinc-100 dark:focus:bg-neutral-700',
])
<div
    class="hs-dropdown [--auto-close:inside] [--placement:{{ $placement }}] relative inline-flex items-center justify-center {{ $backgroundClass }} rounded-lg transition-colors duration-200">
    <button id="{{ $name }}" type="button" name="dropdown-{{ $name }}"
        class="hs-dropdown-toggle py-2 px-2 inline-flex justify-center items-center gap-2 rounded-3xl align-middle disabled:opacity-50 disabled:pointer-events-none"
        aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
        @if (Str::contains($icon, 'svg'))
            {!! $icon !!}
        @elseif($icon)
            <x-wireui-icon name="{{ $icon }}" class="size-5" />
        @endif
    </button>
    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden {{ $minWidth }} {{ $maxWidth }} z-20 bg-zinc-50 shadow-2xl rounded-lg p-2 dark:bg-zinc-900 dark:border dark:border-zinc-700"
        role="menu" aria-orientation="vertical" aria-labelledby="{{ $name }}">
        <div>
            <span class="text-start block py-2 px-1 text-xs font-medium uppercase text-zinc-600 dark:text-zinc-400">
                {{ $title }}
            </span>
            {{ $slot }}
        </div>
    </div>
</div>

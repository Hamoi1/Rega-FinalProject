@props(['active' => false, 'block' => false])

@php
    $classes = $active
        ? 'bg-blue-50/50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold'
        : 'text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/50';

    $baseClasses = $block
        ? 'block px-3 py-2 rounded-md text-base font-medium transition-colors'
        : 'px-3 py-2 rounded-md text-sm font-medium transition-colors';
@endphp

<a {{ $attributes->merge(['class' => "$baseClasses $classes"]) }} wire:navigate>
    {{ $slot }}
</a>

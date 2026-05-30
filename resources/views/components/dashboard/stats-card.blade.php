@props([
    'title' => '',
    'subtitle' => '',
    'count' => 0,
    'icon' => '',
    'iconBgColor' => 'bg-blue-600',
])

<div
    class="rounded-2xl border border-zinc-200 bg-linear-to-br from-white to-zinc-50/70 p-4 shadow-sm dark:border-zinc-700/70 dark:from-zinc-900 dark:to-zinc-800/30">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 space-y-2">
            <p class="truncate text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ $title }}</p>
            <span
                class="inline-flex max-w-full items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                {{ $subtitle }}
            </span>
            <p class="text-3xl font-medium leading-none text-zinc-900 dark:text-white">{{ Number::format($count ?: 0) }}
            </p>
        </div>
        <div
            class="{{ $iconBgColor }} flex size-11 shrink-0 items-center justify-center rounded-xl text-white shadow [&_svg]:size-6 [&_svg]:shrink-0">
            <span aria-hidden="true">{!! $icon !!}</span>
        </div>
    </div>
</div>

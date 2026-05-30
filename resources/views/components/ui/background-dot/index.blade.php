@props([
    'zIndex' => '-z-20',
])
<div
    class="absolute inset-0 h-full w-full bg-transparent
    bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] bg-size-[16px_16px]
    dark:bg-[radial-gradient(#3f3f46_1px,transparent_1px)] dark:bg-size-[16px_16px]
    {{ $zIndex }}">
</div>

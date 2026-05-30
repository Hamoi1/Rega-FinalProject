@props([
    'ids',
    'name' => 'dropdown',
    'icon' => 'adjustments-vertical',
    'minWidth' => 'min-w-32',
    'maxWidth' => 'max-w-48',
])
<x-ui.dropdown placement="bottom-right" :$name :$icon :$minWidth :$maxWidth
    backgroundClass="bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-700 focus:bg-zinc-900 dark:focus:bg-zinc-800 text-white shadow-md">
    <div class="space-y-3 child-not-first:justify-start">
        <p
            class="flex items-center justify-center text-sm border-b border-zinc-200 dark:border-zinc-600 pb-2 text-zinc-800 dark:text-zinc-200">
            @lang('words.selected_records', ['num' => count($ids)])
        </p>
        @if (count($ids) > 0)
            <x-wireui-button class="w-full" flat sm negative type="button" wire:click='clear' wire:loading.attr='disabled'
                wire:target='ids' class="border-b w-full dark:border-zinc-400 dark:hover:bg-zinc-800 dark:text-zinc-100">
                <x-wireui-icon name="x-mark" class="w-5 h-5" />
                @lang('words.clear')
            </x-wireui-button>
        @endif
        @if (isset($prepend))
            {{ $prepend }}
        @endif
        <x-wireui-button class="w-full" flat sm negative type="button" wire:click='deletingRecords'
            name="deletingRecords-button-delete" wire:loading.attr='disabled' wire:target='ids'>
            <x-wireui-icon name="trash" class="w-5 h-5" />
            @lang('words.delete')
        </x-wireui-button>

        @if (isset($append))
            {{ $append }}
        @endif
    </div>
</x-ui.dropdown>

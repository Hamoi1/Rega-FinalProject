<div>
    @include('dashboard.bus-line.add-update-modal')
    @use('App\Enums\StatusEnum')

    <section class="p-1.5 space-y-3">
        <div class="flex items-center justify-between">
            <h1 class="text-xl md:text-2xl">
                @lang('pages.bus_lines.list')
            </h1>
            <div>
                <button name="add-bus-line" class="page-plus-class" type="button" x-on:click="$openModal('AddOrUpdate');">
                    <span class="hidden lg:inline">
                        @lang('pages.bus_lines.create')
                    </span>
                    <x-wireui-icon name="plus" class="w-5 h-5" />
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-4 2xl:grid-cols-6">
            <div class="md:col-span-2">
                <x-wireui-input type="search" icon="magnifying-glass"
                    placeholder="{{ __('words.search', ['attr' => __('pages.bus_lines.single')]) }}"
                    wire:model.live.debounce.300ms="search" />
            </div>
            <div>
                <x-wireui-select wire:model.live.debounce.300ms='from_location' :placeholder="__('pages.bus_lines.from_location')" :async-data="[
                    'api' => route('api.data', ['table' => 'locations', 'locale' => app()->getLocale()]),
                ]"
                    option-label="name" option-value="id"></x-wireui-select>
            </div>
            <div>
                <x-wireui-select wire:model.live.debounce.300ms='to_location' :placeholder="__('pages.bus_lines.to_location')" :async-data="[
                    'api' => route('api.data', ['table' => 'locations', 'locale' => app()->getLocale()]),
                ]"
                    option-label="name" option-value="id"></x-wireui-select>
            </div>
            <div>
                <x-wireui-select wire:model.live.debounce.300ms='status' :placeholder="__('words.status')">
                    <x-wireui-select.option value="">@lang('words.all')</x-wireui-select.option>
                    <x-wireui-select.option value="active">@lang('words.active')</x-wireui-select.option>
                    <x-wireui-select.option value="inactive">@lang('words.inactive')</x-wireui-select.option>
                </x-wireui-select>
            </div>
        </div>
    </section>

    <div class="relative p-1">
        <div wire:loading wire:target='search,nextPage,gotoPage,previousPage,status,from_location,to_location,delete'>
            <x-ui.loading-state />
        </div>
        <x-table.layout>
            <x-slot name="header">
                <th>@lang('pages.bus_lines.from_location')</th>
                <th>@lang('pages.bus_lines.to_location')</th>
                <th>@lang('words.status')</th>
                <th>@lang('words.created_at')</th>
                <th>@lang('words.actions')</th>
            </x-slot>
            <x-slot name="body">
                @forelse ($busLines as $busLine)
                    <tr wire:key='bus-line-row-{{ md5($busLine->id) }}'>
                        <td>{{ $busLine->from_location_name ?? '-' }}</td>
                        <td>{{ $busLine->to_location_name ?? '-' }}</td>
                        <td>
                            @if ($busLine->status->value === 'active' || $busLine->status === 'active')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    @lang('words.active')
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    @lang('words.inactive')
                                </span>
                            @endif
                        </td>
                        <td>
                            {{ $busLine->created_at->diffForHumans() }}
                            <p>
                                <span class="text-sm text-gray-500">
                                    {{ $busLine->created_at->format('Y-m-d h:i:s a') }}
                                </span>
                            </p>
                        </td>
                        <td class="text-center">
                            <x-ui.dropdown name="bus-line-dropdown-actions-{{ md5($busLine->id) }}">
                                <div class="flex flex-col gap-y-2.5">
                                    <div
                                        class="inline-flex items-center justify-center shrink-0 border border-neutral-200 rounded-lg p-1.5 dark:border-neutral-700 ">
                                        <x-wireui-toggle md :left-label="$busLine->status->getLabel()" :checked="$busLine->status === StatusEnum::Active"
                                            x-on:click="$wire.toggleStatus({{ $busLine->id }})" />
                                    </div>
                                    <div>
                                        <x-wireui-button
                                            x-on:click="$wire.set('form.edit', true);$dispatch('editBusLine', {id: {{ $busLine->id }}});$openModal('AddOrUpdate');"
                                            type="button" title="{{ __('pages.bus_lines.edit') }}"
                                            name="edit-{{ md5($busLine->id) }}" primary
                                            class="flex items-center w-full gap-1">
                                            <x-wireui-icon name="pencil" class="size-4" />
                                            <span>
                                                @lang('pages.bus_lines.edit')
                                            </span>
                                        </x-wireui-button>
                                    </div>
                                    <div>
                                        <x-wireui-button negative name="delete-{{ md5($busLine->id) }}" type="button"
                                            wire:click="delete({{ $busLine->id }})"
                                            wire:confirm="{{ __('messages.confirm_delete', ['attr' => ($busLine->from_location_name ?? '') . ' → ' . ($busLine->to_location_name ?? '')]) }}"
                                            title="{{ __('pages.bus_lines.delete') }}"
                                            class="flex items-center w-full gap-1">
                                            <x-wireui-icon name="trash" class="size-4" />
                                            <span>
                                                @lang('words.delete')
                                            </span>
                                        </x-wireui-button>
                                    </div>
                                </div>
                            </x-ui.dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-table.empty-state />
                        </td>
                    </tr>
                @endforelse
            </x-slot>
            <x-slot name="links">
                {{ $busLines->onEachSide(1)->links() }}
            </x-slot>
        </x-table.layout>
    </div>
</div>

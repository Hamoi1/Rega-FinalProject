<div>
    @include('dashboard.location.add-update-modal')
    <section class="p-1.5 space-y-3">
        <div class="flex items-center justify-between">
            <h1 class="text-xl md:text-2xl">
                @lang('pages.locations.list')
            </h1>
            <div>
                <button name="add-location" class="page-plus-class" type="button"
                    x-on:click="$openModal('AddOrUpdate');$dispatch('init-map-component',{name:'AddOrUpdate'});">
                    <span class="hidden lg:inline">
                        @lang('pages.locations.create')
                    </span>
                    <x-wireui-icon name="plus" class="w-5 h-5" />
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-4 2xl:grid-cols-6">
            <div class="md:col-span-2">
                <x-wireui-input type="search" icon="magnifying-glass"
                    placeholder="{{ __('words.search', ['attr' => __('pages.locations.single')]) }}"
                    wire:model.live.debounce.300ms="search" />
            </div>
            <div>
                <x-wireui-select wire:model.live.debounce.300ms='city' :placeholder="__('words.select_', ['attr' => __('pages.cities.single')])" :async-data="[
                    'api' => route('api.data', ['table' => 'cities', 'locale' => app()->getLocale()]),
                ]"
                    option-label="name" option-value="id"></x-wireui-select>
            </div>
        </div>
    </section>

    <div class="relative p-1">
        <div wire:loading wire:target='search,nextPage,gotoPage,previousPage,city,delete'>
            <x-ui.loading-state />
        </div>
        <x-table.layout>
            <x-slot name="header">
                <th>@lang('words.city')</th>
                <th>@lang('words.name_en')</th>
                <th>@lang('words.name_ar')</th>
                <th>@lang('words.name_ckb')</th>
                <th>@lang('words.map_location')</th>
                <th>@lang('words.created_at')</th>
                <th>@lang('words.actions')</th>
            </x-slot>
            <x-slot name="body">
                @forelse ($locations as $location)
                    <tr wire:key='location-row-{{ md5($location->id) }}'>
                        <td>{{ $location->city?->getTranslation('name', app()->getLocale()) ?? '-' }}</td>
                        <td>{{ $location->getTranslation('name', 'en') }}</td>
                        <td>{{ $location->getTranslation('name', 'ar') }}</td>
                        <td>{{ $location->getTranslation('name', 'ckb') }}</td>
                        <td>
                            @if ($location->map_location && isset($location->map_location['lat']))
                                <span class="text-xs text-gray-500">
                                    {{ number_format($location->map_location['lat'] ?? 0, 6) }},
                                    {{ number_format($location->map_location['lng'] ?? 0, 6) }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td>
                            {{ $location->created_at->diffForHumans() }}
                            <p>
                                <span class="text-sm text-gray-500">
                                    {{ $location->created_at->format('Y-m-d h:i:s a') }}
                                </span>
                            </p>
                        </td>
                        <td class="text-center">
                            <x-ui.dropdown name="location-dropdown-actions-{{ md5($location->id) }}">
                                <div class="flex flex-col gap-y-2.5">
                                    <div>
                                        <x-wireui-button
                                            x-on:click="$wire.set('form.edit', true);$dispatch('editLocation', {id: {{ $location->id }}});$openModal('AddOrUpdate');"
                                            type="button" title="{{ __('pages.locations.edit') }}"
                                            name="edit-{{ md5($location->id) }}" primary
                                            class="flex items-center w-full gap-1">
                                            <x-wireui-icon name="pencil" class="size-4" />
                                            <span>
                                                @lang('pages.locations.edit')
                                            </span>
                                        </x-wireui-button>
                                    </div>
                                    <div>
                                        <x-wireui-button negative name="delete-{{ md5($location->id) }}" type="button"
                                            wire:click="delete({{ $location->id }})"
                                            wire:confirm="{{ __('messages.confirm_delete', ['attr' => $location->getTranslation('name', app()->getLocale())]) }}"
                                            title="{{ __('pages.locations.delete') . ' ' . $location->getTranslation('name', app()->getLocale()) }}"
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
                        <td colspan="7">
                            <x-table.empty-state />
                        </td>
                    </tr>
                @endforelse
            </x-slot>
            <x-slot name="links">
                {{ $locations->onEachSide(1)->links() }}
            </x-slot>
        </x-table.layout>
    </div>
</div>

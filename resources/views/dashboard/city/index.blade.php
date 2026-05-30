<div>
    @include('dashboard.city.add-update-modal')
    <section class="p-1.5 space-y-3">
        <div class="flex items-center justify-between">
            <h1 class="text-xl md:text-2xl">
                @lang('pages.cities.list')
            </h1>
            <div>
                <button name="add-city" class="page-plus-class" type="button" x-on:click="$openModal('AddOrUpdate')">
                    <span class="hidden lg:inline">
                        @lang('pages.cities.create')
                    </span>
                    <x-wireui-icon name="plus" class="w-5 h-5" />
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-4 2xl:grid-cols-6">
            <div class="md:col-span-2">
                <x-wireui-input type="search" icon="magnifying-glass"
                    placeholder="{{ __('words.search', ['attr' => __('pages.cities.single')]) }}"
                    wire:model.live.debounce.300ms="search" />
            </div>
        </div>
    </section>
    <div class="relative p-1">
        <div wire:loading wire:target='search,nextPage,gotoPage,previousPage,delete'>
            <x-ui.loading-state />
        </div>
        <x-table.layout>
            <x-slot name="header">
                <th>@lang('words.name_en')</th>
                <th>@lang('words.name_ar')</th>
                <th>@lang('words.name_ckb')</th>
                <th>@lang('words.created_at')</th>
                <th>@lang('words.actions')</th>
            </x-slot>
            <x-slot name="body">
                @forelse ($cities as $city)
                    <tr wire:key='city-row-{{ md5($city->id) }}'>
                        <td>{{ $city->getTranslation('name', 'en') }}</td>
                        <td>{{ $city->getTranslation('name', 'ar') }}</td>
                        <td>{{ $city->getTranslation('name', 'ckb') }}</td>
                        <td>
                            {{ $city->created_at->diffForHumans() }}
                            <p>
                                <span class="text-sm text-gray-500">
                                    {{ $city->created_at->format('Y-m-d h:i:s a') }}
                                </span>
                            </p>
                        </td>
                        <td class="text-center">
                            <x-ui.dropdown name="city-dropdown-actions-{{ md5($city->id) }}">
                                <div class="flex flex-col gap-y-2.5">
                                    <div>
                                        <x-wireui-button
                                            x-on:click="$wire.set('form.edit', true);$dispatch('editCity', {id: {{ $city->id }}});$openModal('AddOrUpdate');"
                                            type="button" title="{{ __('pages.cities.edit') }}"
                                            name="edit-{{ md5($city->id) }}" primary
                                            class="flex items-center w-full gap-1">
                                            <x-wireui-icon name="pencil" class="size-4" />
                                            <span>
                                                @lang('pages.cities.edit')
                                            </span>
                                        </x-wireui-button>
                                    </div>
                                    <div>
                                        <x-wireui-button negative name="delete-{{ md5($city->id) }}" type="button"
                                            wire:click="delete({{ $city->id }})"
                                            wire:confirm="{{ __('messages.confirm_delete', ['attr' => $city->getTranslation('name', app()->getLocale())]) }}"
                                            title="{{ __('pages.cities.delete') . ' ' . $city->getTranslation('name', app()->getLocale()) }}"
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
                        <td colspan="21">
                            <x-table.empty-state />
                        </td>
                    </tr>
                @endforelse
            </x-slot>
            <x-slot name="links">
                {{ $cities->onEachSide(1)->links() }}
            </x-slot>
        </x-table.layout>
    </div>
</div>

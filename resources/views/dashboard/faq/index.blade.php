<div>
    @include('dashboard.faq.add-update-modal')
    <section class="p-1.5 space-y-3">
        <div class="flex items-center justify-between">
            <h1 class="text-xl md:text-2xl">
                @lang('pages.faqs.list')
            </h1>
            <div>
                <button name="add-faq" class="page-plus-class" type="button" x-on:click="$openModal('AddOrUpdate')">
                    <span class="hidden lg:inline">
                        @lang('pages.faqs.create')
                    </span>
                    <x-wireui-icon name="plus" class="w-5 h-5" />
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-4 2xl:grid-cols-6">
            <div class="md:col-span-2">
                <x-wireui-input type="search" icon="magnifying-glass"
                    placeholder="{{ __('words.search', ['attr' => __('pages.faqs.single')]) }}"
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
                <th>@lang('words.question_en')</th>
                <th>@lang('words.question_ar')</th>
                <th>@lang('words.question_ckb')</th>
                <th>@lang('words.created_at')</th>
                <th>@lang('words.actions')</th>
            </x-slot>
            <x-slot name="body">
                @forelse ($faqs as $faq)
                    <tr wire:key='faq-row-{{ md5($faq->id) }}'>
                        <td class="max-w-xs truncate">{{ $faq->getTranslation('question', 'en') }}</td>
                        <td class="max-w-xs truncate">{{ $faq->getTranslation('question', 'ar') }}</td>
                        <td class="max-w-xs truncate">{{ $faq->getTranslation('question', 'ckb') }}</td>
                        <td>
                            {{ $faq->created_at->diffForHumans() }}
                            <p>
                                <span class="text-sm text-gray-500">
                                    {{ $faq->created_at->format('Y-m-d h:i:s a') }}
                                </span>
                            </p>
                        </td>
                        <td class="text-center">
                            <x-ui.dropdown name="faq-dropdown-actions-{{ md5($faq->id) }}">
                                <div class="flex flex-col gap-y-2.5">
                                    <div>
                                        <x-wireui-button
                                            x-on:click="$wire.set('form.edit', true);$dispatch('editFaq', {id: {{ $faq->id }}});$openModal('AddOrUpdate');"
                                            type="button" title="{{ __('pages.faqs.edit') }}"
                                            name="edit-{{ md5($faq->id) }}" primary
                                            class="flex items-center w-full gap-1">
                                            <x-wireui-icon name="pencil" class="size-4" />
                                            <span>
                                                @lang('pages.faqs.edit')
                                            </span>
                                        </x-wireui-button>
                                    </div>
                                    <div>
                                        <x-wireui-button negative name="delete-{{ md5($faq->id) }}" type="button"
                                            wire:click="delete({{ $faq->id }})"
                                            wire:confirm="{{ __('messages.confirm_delete', ['attr' => $faq->getTranslation('question', app()->getLocale())]) }}"
                                            title="{{ __('pages.faqs.delete') }}"
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
                {{ $faqs->onEachSide(1)->links() }}
            </x-slot>
        </x-table.layout>
    </div>
</div>

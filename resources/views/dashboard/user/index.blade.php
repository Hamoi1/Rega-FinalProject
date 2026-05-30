<div>
    @use('App\Enums\StatusEnum')
    @if ($type === 'admin')
        @include('dashboard.user.add-update-modal')
    @endif
    <section class="p-1.5 space-y-3">
        <div class="flex items-center justify-between">
            <h1 class="text-xl md:text-2xl">
                @lang('pages.users.list') ({{ $this->type }})
            </h1>
            @if ($type === 'admin')
                <div>
                    <button name="add-user" class="page-plus-class" type="button" x-on:click="$openModal('AddOrUpdate')">
                        <span class="hidden lg:inline">
                            @lang('pages.users.create')
                        </span>
                        <x-wireui-icon name="plus" class="w-5 h-5" />
                    </button>
                </div>
            @endif
        </div>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-4 2xl:grid-cols-6">
            <div class="md:col-span-2">
                <x-wireui-input type="search" icon="magnifying-glass"
                    placeholder="{{ __('words.search', ['attr' => __('pages.users.single')]) }}"
                    wire:model.live.debounce.300ms="search" />
            </div>
            <div>
                <x-wireui-select wire:model.live.debounce.300ms='status' :options="[['value' => 'all', 'label' => __('words.all')], ...StatusEnum::getArraySelect()]"
                    placeholder="{{ __('words.select_', ['attr' => __('words.status')]) }}" option-label="label"
                    option-value="value" />
            </div>
        </div>
    </section>
    <div class="relative p-1">
        <div wire:loading wire:target='search,nextPage,gotoPage,previousPage,status,delete,toggleStatus'>
            <x-ui.loading-state />
        </div>
        <x-table.layout>
            <x-slot name="header">
                <th>@lang('words.name')</th>
                <th>@lang('words.username')</th>
                <th>@lang('words.phone')</th>
                <th>@lang('words.email')</th>
                <th>@lang('words.status')</th>
                <th>@lang('words.created_at')</th>
                <th>@lang('words.actions')</th>
            </x-slot>
            <x-slot name="body">
                @forelse ($users as $user)
                    <tr wire:key='user-row-{{ md5($user->id) }}'>
                        <td>
                            {{ $user->name }}
                        </td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{!! $user->status->getHtmlLabel() !!}</td>
                        <td>
                            {{ $user->created_at->diffForHumans() }}
                            <p>
                                <span class="text-sm text-gray-500">
                                    {{ $user->created_at->format('Y-m-d h:i:s a') }}
                                </span>
                            </p>
                        </td>
                        <td class="text-center">
                            <x-ui.dropdown name="user-dropdown-actions-{{ md5($user->id) }}">
                                <div class="flex flex-col gap-y-2.5">
                                    <div
                                        class="inline-flex items-center justify-center shrink-0 border border-neutral-200 rounded-lg p-1.5 dark:border-neutral-700 ">
                                        <x-wireui-toggle md :left-label="$user->status->getLabel()" :checked="$user->status === StatusEnum::Active"
                                            wire:click="toggleStatus({{ $user->id }})"
                                            wire:confirm="{{ __('messages.confirm_toggle_status', ['attr' => $user->name]) }}" />
                                    </div>
                                    @if ($type === 'admin')
                                        <div>
                                            <x-wireui-button
                                                x-on:click="$wire.set('form.edit', true);$dispatch('editUser', {id: {{ $user->id }}});$openModal('AddOrUpdate');"
                                                type="button" title="{{ __('pages.users.edit') }}"
                                                name="edit-{{ md5($user->id) }}" primary
                                                class="flex items-center w-full gap-1">
                                                <x-wireui-icon name="pencil" class="size-4" />
                                                <span>
                                                    @lang('pages.users.edit')
                                                </span>
                                            </x-wireui-button>
                                        </div>
                                        <div>
                                            <x-wireui-button negative name="delete-{{ md5($user->id) }}" type="button"
                                                wire:click="delete({{ $user->id }})"
                                                wire:confirm="{{ __('messages.confirm_delete', ['attr' => $user->name]) }}"
                                                title="{{ __('pages.users.delete') . ' ' . $user->name }}"
                                                class="flex items-center w-full gap-1">
                                                <x-wireui-icon name="trash" class="size-4" />
                                                <span>
                                                    @lang('pages.users.delete')
                                                </span>
                                            </x-wireui-button>
                                        </div>
                                    @endif
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
                {{ $users->onEachSide(1)->links() }}
            </x-slot>
        </x-table.layout>
    </div>
</div>

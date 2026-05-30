@use('App\Enums\StatusEnum')
<x-wireui-modal blur="md" name="AddOrUpdate" persistent x-on:keydown.escape="close;$wire.done();"
    x-on:closing-modal.window="close;" width="3xl" align="center">
    <x-wireui-card class="w-full z-70" loading="form.edit" :title="!$form->edit ? __('pages.bus_lines.create') : __('pages.bus_lines.edit')">
        <x-slot name="action">
            <x-wireui-mini-button rounded x-on:click="close;$wire.done()" name="close-bus-line-modal" flat>
                <x-wireui-icon name="x-mark" class="w-5 h-5" />
            </x-wireui-mini-button>
        </x-slot>
        <div wire:loading.remove wire:target='form.edit' class="block w-full">
            <form wire:submit='submit' class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <div>
                    <x-wireui-select wire:model='form.from_location_id' :label="__('pages.bus_lines.from_location')" :placeholder="__('words.select_', ['attr' => __('pages.locations.single')])"
                        :async-data="[
                            'api' => route('api.data', ['table' => 'locations', 'locale' => app()->getLocale()]),
                        ]" option-label="name" option-value="id">
                    </x-wireui-select>
                </div>
                <div>
                    <x-wireui-select wire:model='form.to_location_id' :label="__('pages.bus_lines.to_location')" :placeholder="__('words.select_', ['attr' => __('pages.locations.single')])"
                        :async-data="[
                            'api' => route('api.data', ['table' => 'locations', 'locale' => app()->getLocale()]),
                        ]" option-label="name" option-value="id">
                    </x-wireui-select>
                </div>
                <div class="col-span-full">
                    <x-wireui-select wire:model='form.status' :label="__('words.status')" :placeholder="__('words.select_', ['attr' => __('words.status')])" :options="[...StatusEnum::getArraySelect()]"
                        option-label="label" option-value="value" />
                </div>
                <div class="col-span-full">
                    <div class="col-span-full">
                        <x-wireui-input wire:model.live='form.route_json_file' :label="__('pages.bus_lines.route_file')" :placeholder="__('words.upload_', ['attr' => __('pages.bus_lines.route_file')])"
                            type="file" accept=".json" />

                        @if ($form->edit && is_string($form->route_json_file) && !empty($form->route_json_file))
                            <div class="flex items-center gap-2 mt-2">
                                <a href="{{ $form->route_json_file }}" target="_blank"
                                    class="text-sm text-blue-600 underline">
                                    {{ basename($form->route_json_file) }}
                                </a>
                                <button type="button" wire:click="$set('form.route_json_file', null)"
                                    class="text-sm text-red-500 hover:underline">
                                    {{ __('words.clear') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-span-full flex justify-end gap-x-4 mt-6">
                    <x-wireui-button name="cancel" type='button' negative flat :label="__('words.cancel')" spinner='done'
                        x-on:click="close;$wire.done()" />
                    <x-wireui-button name="submit" positive type="submit" :label="__('words.save')" spinner='submit' />
                </div>
            </form>
        </div>
        <div wire:loading wire:target='form.edit'>
            <x-ui.modal-loading />
        </div>
    </x-wireui-card>
</x-wireui-modal>

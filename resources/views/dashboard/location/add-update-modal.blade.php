<x-wireui-modal blur="md" name="AddOrUpdate" persistent
    x-on:keydown.escape="close;$dispatch('clear-map-data');$wire.done();"
    x-on:closing-modal.window="close;$dispatch('clear-map-data');" width="5xl" align="center">
    <x-wireui-card class="w-full z-70" loading="form.edit" :title="!$form->edit ? __('pages.locations.create') : __('pages.locations.edit')">
        <x-slot name="action">
            <x-wireui-mini-button rounded x-on:click="close;$dispatch('clear-map-data');$wire.done()"
                name="close-location-modal" flat>
                <x-wireui-icon name="x-mark" class="w-5 h-5" />
            </x-wireui-mini-button>
        </x-slot>
        <div wire:loading.remove wire:target='form.edit' class="block w-full"
            x-on:load-map-data-location-map.window="
                setTimeout(() => {
                    $dispatch('load-map-data', $event.detail);
                 }, 100);
        
        ">
            <form wire:submit='submit' class="grid grid-cols-1 md:grid-cols-2 gap-3 w-full">
                <div class="col-span-full">
                    <x-wireui-select wire:model='form.city_id' :label="__('pages.cities.single')" :placeholder="__('words.select_', ['attr' => __('pages.cities.single')])" :async-data="[
                        'api' => route('api.data', ['table' => 'cities', 'locale' => app()->getLocale()]),
                    ]"
                        option-label="name" option-value="id">
                    </x-wireui-select>
                </div>
                <div>
                    <x-wireui-input wire:model='form.name_en' :label="__('words.name_en')" :placeholder="__('words.enter_', ['attr' => __('words.name_en')])" />
                </div>
                <div>
                    <x-wireui-input wire:model='form.name_ar' :label="__('words.name_ar')" :placeholder="__('words.enter_', ['attr' => __('words.name_ar')])" />
                </div>
                <div>
                    <x-wireui-input wire:model='form.name_ckb' :label="__('words.name_ckb')" :placeholder="__('words.enter_', ['attr' => __('words.name_ckb')])" />
                </div>
                <div class="col-span-full h-130">
                    <x-ui.map wire:model='form.map_location' id="location-map" :label="__('words.map_location')"
                        modalName="AddOrUpdate" />
                </div>
                <div class="col-span-full flex justify-end gap-x-4 mt-10">
                    <x-wireui-button name="cancel" type='button' negative flat :label="__('words.cancel')" spinner='done'
                        x-on:click="close;$dispatch('clear-map-data');$wire.done()" />
                    <x-wireui-button name="submit" positive type="submit" :label="__('words.save')" spinner='submit' />
                </div>
            </form>
        </div>
        <div wire:loading wire:target='form.edit'>
            <x-ui.modal-loading />
        </div>
    </x-wireui-card>
</x-wireui-modal>

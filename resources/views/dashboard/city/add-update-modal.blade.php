<x-wireui-modal blur="md" name="AddOrUpdate" persistent x-on:keydown.escape="close;$wire.done();"
    x-on:closing-modal.window="close;" width="lg" align="center">
    <x-wireui-card class="w-full z-70" loading="form.edit" :title="!$form->edit ? __('pages.cities.create') : __('pages.cities.edit')">
        <x-slot name="action">
            <x-wireui-mini-button rounded x-on:click="close;$wire.done()" name="close-city-modal" flat>
                <x-wireui-icon name="x-mark" class="w-5 h-5" />
            </x-wireui-mini-button>
        </x-slot>
        <div wire:loading.remove wire:target='form.edit' class="block w-full">
            <form wire:submit='submit' class="space-y-6 w-full">
                <div>
                    <x-wireui-input wire:model='form.name_en' :label="__('words.name_en')" :placeholder="__('words.enter_', ['attr' => __('words.name_en')])" />
                </div>
                <div>
                    <x-wireui-input wire:model='form.name_ar' :label="__('words.name_ar')" :placeholder="__('words.enter_', ['attr' => __('words.name_ar')])" />
                </div>
                <div>
                    <x-wireui-input wire:model='form.name_ckb' :label="__('words.name_ckb')" :placeholder="__('words.enter_', ['attr' => __('words.name_ckb')])" />
                </div>
                <div class="flex justify-end gap-x-4 mt-6">
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

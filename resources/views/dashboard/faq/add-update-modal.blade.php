<x-wireui-modal blur="md" name="AddOrUpdate" persistent x-on:keydown.escape="close;$wire.done();"
    x-on:closing-modal.window="close;" width="6xl" align="center">
    <x-wireui-card class="w-full z-70" loading="form.edit" :title="!$form->edit ? __('pages.faqs.create') : __('pages.faqs.edit')">
        <x-slot name="action">
            <x-wireui-mini-button rounded x-on:click="close;$wire.done()" name="close-faq-modal" flat>
                <x-wireui-icon name="x-mark" class="w-5 h-5" />
            </x-wireui-mini-button>
        </x-slot>
        <div wire:loading.remove wire:target='form.edit' class="block w-full">
            <form wire:submit='submit' class="grid gap-4 grid-cols-1 xl:grid-cols-3 w-full">
                {{-- Questions --}}
                <x-wireui-textarea wire:model='form.question_en' :label="__('words.question_en')" :placeholder="__('words.enter_', ['attr' => __('words.question_en')])" />
                <x-wireui-textarea wire:model='form.question_ar' :label="__('words.question_ar')" :placeholder="__('words.enter_', ['attr' => __('words.question_ar')])" />
                <x-wireui-textarea wire:model='form.question_ckb' :label="__('words.question_ckb')" :placeholder="__('words.enter_', ['attr' => __('words.question_ckb')])" />

                {{-- Answers --}}
                <x-wireui-textarea wire:model='form.answer_en' :label="__('words.answer_en')" :placeholder="__('words.enter_', ['attr' => __('words.answer_en')])" />
                <x-wireui-textarea wire:model='form.answer_ar' :label="__('words.answer_ar')" :placeholder="__('words.enter_', ['attr' => __('words.answer_ar')])" />
                <x-wireui-textarea wire:model='form.answer_ckb' :label="__('words.answer_ckb')" :placeholder="__('words.enter_', ['attr' => __('words.answer_ckb')])" />

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

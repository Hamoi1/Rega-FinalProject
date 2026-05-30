<?php

namespace App\Http\Controllers\Dashboard\Faq;

use App\Livewire\Forms\FaqForm;
use App\Models\Faq;
use App\Traits\WithNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

#[Title('Faq Management')]
class Index extends Component
{
    use WithNotification;
    use WithPagination;

    public FaqForm $form;

    #[Url()]
    public string $search = '';

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $faqs = Faq::search($this->search)
            ->latest()
            ->paginate(20);

        return view('dashboard.faq.index', ['faqs' => $faqs]);
    }

    public function updated($property, $value): void
    {
        if ('search' === $property) {
            $this->resetPage();
        }
    }

    public function submit(): void
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $data = [
                'question' => [
                    'en' => $this->form->question_en,
                    'ckb' => $this->form->question_ckb,
                    'ar' => $this->form->question_ar,
                ],
                'answer' => [
                    'en' => $this->form->answer_en,
                    'ckb' => $this->form->answer_ckb,
                    'ar' => $this->form->answer_ar,
                ],
            ];

            $faq = Faq::updateOrCreate([
                'id' => $this->form->edit ? $this->form->faq_id : null,
            ], $data);
        } catch (Throwable $throwable) {
            DB::rollBack();
            $this->error(__('messages.something_went_wrong'));
        } finally {
            if (isset($throwable)) {
                return;
            }

            $this->success(
                __($faq->wasRecentlyCreated ? 'messages.created_successfully'
                    : 'messages.updated_successfully', ['attr' => __('pages.faqs.single')]),
            );
            DB::commit();
            $this->done();
        }
    }

    #[On('editFaq')]
    public function edit($id): void
    {
        $faq = Faq::findOrFail($id);
        $this->form->edit = true;
        $this->form->faq_id = $faq->id;

        $this->form->question_en = $faq->getTranslation('question', 'en');
        $this->form->question_ar = $faq->getTranslation('question', 'ar');
        $this->form->question_ckb = $faq->getTranslation('question', 'ckb');

        $this->form->answer_en = $faq->getTranslation('answer', 'en');
        $this->form->answer_ar = $faq->getTranslation('answer', 'ar');
        $this->form->answer_ckb = $faq->getTranslation('answer', 'ckb');
    }

    public function delete($id): void
    {
        Faq::findOrFail($id)->delete();
        $this->success(__('messages.deleted_successfully', ['attr' => __('pages.faqs.single')]));
    }


    public function done(): void
    {
        $this->dispatch('closing-modal');
        $this->form->reset();
        $this->resetValidation();
        $this->resetErrorBag();
    }
}

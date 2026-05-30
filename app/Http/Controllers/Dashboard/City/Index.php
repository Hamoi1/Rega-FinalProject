<?php

namespace App\Http\Controllers\Dashboard\City;

use App\Livewire\Forms\CityForm;
use App\Models\City;
use App\Traits\WithNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

#[Title('City Management')]
class Index extends Component
{
    use WithNotification;
    use WithPagination;

    public CityForm $form;

    #[Url()]
    public string $search = '';

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $cities = City::search($this->search)
            ->latest()
            ->paginate(20);

        return view('dashboard.city.index', ['cities' => $cities]);
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
                'name' => [
                    'en' => $this->form->name_en,
                    'ckb' => $this->form->name_ckb,
                    'ar' => $this->form->name_ar,
                ],
            ];

            $city = City::updateOrCreate([
                'id' => $this->form->edit ? $this->form->city_id : null,
            ], $data);
        } catch (Throwable $throwable) {
            DB::rollBack();
            $this->error(__('messages.something_went_wrong'));
        } finally {
            if (isset($throwable)) {
                return;
            }

            $this->success(
                __($city->wasRecentlyCreated ? 'messages.created_successfully'
                    : 'messages.updated_successfully', ['attr' => __('pages.cities.single')]),
            );
            DB::commit();
            $this->done();
        }
    }

    #[On('editCity')]
    public function edit($id): void
    {
        $city = City::findOrFail($id);
        $this->form->edit = true;
        $this->form->city_id = $city->id;
        $this->form->name_en = $city->getTranslation('name', 'en');
        $this->form->name_ar = $city->getTranslation('name', 'ar');
        $this->form->name_ckb = $city->getTranslation('name', 'ckb');
    }

    public function delete($id): void
    {
        City::findOrFail($id)->delete();
        $this->success(__('messages.deleted_successfully', ['attr' => __('pages.cities.single')]));
    }


    public function done(): void
    {
        $this->dispatch('closing-modal');
        $this->form->reset();
        $this->resetValidation();
        $this->resetErrorBag();
    }
}

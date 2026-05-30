<?php

namespace App\Http\Controllers\Dashboard\Location;

use App\Livewire\Forms\LocationForm;
use App\Models\Location;
use App\Traits\WithNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

#[Title('Location Management')]
class Index extends Component
{
    use WithNotification;
    use WithPagination;

    public LocationForm $form;

    #[Url()]
    public string $search = '';

    #[Url()]
    public $city;

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $locations = Location::search($this->search)
            ->when($this->city, fn($q) => $q->where('city_id', $this->city))
            ->with('city')
            ->latest()
            ->paginate(20);

        return view('dashboard.location.index', ['locations' => $locations]);
    }

    public function updated($property, $value): void
    {
        if (in_array($property, ['search', 'city'])) {
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
                'city_id' => $this->form->city_id,
                'map_location' => $this->form->map_location,
            ];

            $location = Location::updateOrCreate([
                'id' => $this->form->edit ? $this->form->location_id : null,
            ], $data);
        } catch (Throwable $throwable) {
            DB::rollBack();
            $this->error(__('messages.something_went_wrong'));
        } finally {
            if (isset($throwable)) {
                return;
            }

            $this->success(
                __($location->wasRecentlyCreated ? 'messages.created_successfully'
                    : 'messages.updated_successfully', ['attr' => __('pages.locations.single')]),
            );
            DB::commit();
            $this->done();
        }
    }

    #[On('editLocation')]
    public function edit($id): void
    {
        $location = Location::findOrFail($id);
        $this->form->edit = true;
        $this->form->location_id = $location->id;
        $this->form->city_id = $location->city_id;
        $this->form->name_en = $location->getTranslation('name', 'en');
        $this->form->name_ar = $location->getTranslation('name', 'ar');
        $this->form->name_ckb = $location->getTranslation('name', 'ckb');
        $this->form->map_location = $location->map_location ?? ['lat' => null, 'lng' => null];
        $this->dispatch('load-map-data-location-map', [
            'mapId' => 'location-map',
            'marks' => [['lat' => $this->form->map_location['lat'], 'lng' => $this->form->map_location['lng'], 'name' => $this->form->name_en]],
        ]);
    }

    public function delete($id): void
    {
        Location::findOrFail($id)->delete();
        $this->success(__('messages.deleted_successfully', ['attr' => __('pages.locations.single')]));
    }


    public function done(): void
    {
        $this->dispatch('closing-modal');
        $this->form->reset();
        $this->resetValidation();
        $this->resetErrorBag();
    }
}

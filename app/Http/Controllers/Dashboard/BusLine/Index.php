<?php

namespace App\Http\Controllers\Dashboard\BusLine;

use App\Enums\StatusEnum;
use App\Livewire\Forms\BusLineForm;
use App\Models\BusLine;
use App\Traits\WithNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

#[Title('Bus Line Management')]
class Index extends Component
{
    use WithFileUploads;
    use WithNotification;
    use WithPagination;

    public BusLineForm $form;

    #[Url()]
    public string $search = '';

    #[Url()]
    public $status;

    #[Url()]
    public $from_location;

    #[Url()]
    public $to_location;

    #[Locked]
    public $selectedBusLine;

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $locale = app()->getLocale();
        $busLines = BusLine::query()
            ->withAggregate('fromLocation as from_location_name', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.' . $locale . '"))'))
            ->withAggregate('toLocation as to_location_name', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.' . $locale . '"))'))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->from_location, fn($q) => $q->where('from_location_id', $this->from_location))
            ->when($this->to_location, fn($q) => $q->where('to_location_id', $this->to_location))
            ->search($this->search)
            ->latest()
            ->paginate(20);

        return view('dashboard.bus-line.index', ['busLines' => $busLines]);
    }

    public function updated($property, $value): void
    {
        if (in_array($property, ['search', 'status', 'from_location', 'to_location'])) {

            if ('status' === $property) {
                $this->{$property} = in_array($value, StatusEnum::toArray()) ? $value : 'all';
            }

            $this->resetPage();
        }
    }

    public function submit(): void
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $data = [
                'from_location_id' => $this->form->from_location_id,
                'to_location_id' => $this->form->to_location_id,
                'status' => $this->form->status,
            ];

            $busLine = BusLine::updateOrCreate([
                'id' => $this->form->edit ? $this->form->bus_line_id : null,
            ], $data);

            if ($this->form->route_json_file && ! is_string($this->form->route_json_file)) {
                $busLine->addMedia($this->form->route_json_file->getRealPath())
                    ->usingFileName('route_' . $busLine->id . '.json')
                    ->toMediaCollection('route_json_file');
            } elseif (null === $this->form->route_json_file) {
                $busLine->clearMediaCollection('route_json_file');
            }
        } catch (Throwable $throwable) {
            DB::rollBack();
            $this->error(__('messages.something_went_wrong'));
        } finally {
            if (isset($throwable)) {
                return;
            }

            $this->success(
                __($busLine->wasRecentlyCreated ? 'messages.created_successfully'
                    : 'messages.updated_successfully', ['attr' => __('pages.bus_lines.single')]),
            );
            DB::commit();
            $this->done();
        }
    }

    #[On('editBusLine')]
    public function edit($id): void
    {
        $busLine = BusLine::findOrFail($id);
        $this->form->edit = true;
        $this->form->bus_line_id = $busLine->id;
        $this->form->from_location_id = $busLine->from_location_id;
        $this->form->to_location_id = $busLine->to_location_id;
        $this->form->status = $busLine->status->value ?? $busLine->status;
        $this->form->route_json_file = $busLine->route_json_file;
    }

    #[On('viewBusLine')]
    public function view($id): void
    {
        $locale = app()->getLocale();
        $this->selectedBusLine = BusLine::withAggregate('fromLocation as from_location_name', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.' . $locale . '"))'))
            ->withAggregate('toLocation as to_location_name', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.' . $locale . '"))'))
            ->findOrFail($id);

        // Dispatch event with new route data for the map component
        $this->dispatch('load-map-data', [
            'mapId' => 'map-bus-view',
            'geoJsonUrl' => $this->selectedBusLine->route_json_file,
            'routeStartName' => $this->selectedBusLine->from_location_name,
            'routeEndName' => $this->selectedBusLine->to_location_name,
        ]);
    }

    public function delete($id): void
    {
        BusLine::findOrFail($id)->delete();
        $this->success(__('messages.deleted_successfully', ['attr' => __('pages.bus_lines.single')]));
    }

    public function toggleStatus($id): void
    {
        $busLine = BusLine::findOrFail($id);
        $busLine->update(['status' => StatusEnum::Active === $busLine->status ? StatusEnum::Inactive : StatusEnum::Active]);
        $this->success(__('messages.updated_successfully', ['attr' => __('pages.bus_lines.single')]));
    }


    public function done(): void
    {
        $this->dispatch('closing-modal');
        $this->form->reset();
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset('selectedBusLine');
    }
}

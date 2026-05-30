<?php

namespace App\Livewire\Forms;

use App\Enums\StatusEnum;
use Livewire\Form;

class BusLineForm extends Form
{
    public $edit = false;

    public $bus_line_id;

    public $from_location_id;

    public $to_location_id;

    public $status = StatusEnum::Active->value;

    public $route_json_file;

    protected function rules(): array
    {
        return [
            'from_location_id' => ['required', 'exists:locations,id'],
            'to_location_id' => ['required', 'exists:locations,id', 'different:from_location_id','unique:bus_lines,to_location_id,' . $this->bus_line_id . ',id,from_location_id,' . $this->from_location_id . ',to_location_id,' . $this->to_location_id],
            'status' => ['required', 'in:' . implode(',', StatusEnum::toArray())],
            'route_json_file'
            => $this->edit && is_string($this->route_json_file) ? ['nullable', 'url']
            : ['nullable','file','mimes:json','max:12288'],
        ];
    }

    protected function messages(): array
    {
        return [
            'to_location_id.different' => __('validation.different', [
                'attribute' => __('pages.bus_lines.to_location'),
                'other' => __('pages.bus_lines.from_location'),
            ]),
        ];
    }
}

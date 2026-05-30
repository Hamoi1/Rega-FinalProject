<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class LocationForm extends Form
{
    public $edit = false;

    public $location_id;

    public $city_id;

    public $name_en;

    public $name_ar;

    public $name_ckb;

    public $map_location = [
        'lat' => null,
        'lng' => null,
    ];

    protected function rules(): array
    {
        return [
            'city_id' => ['required', 'exists:cities,id'],
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_ckb' => ['required', 'string', 'max:255'],
            'map_location' => ['required', 'array'],
            'map_location.lat' => ['required', 'numeric'],
            'map_location.lng' => ['required', 'numeric'],
        ];
    }
}

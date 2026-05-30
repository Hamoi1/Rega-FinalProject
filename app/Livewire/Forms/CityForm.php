<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class CityForm extends Form
{
    public $edit = false;

    public $city_id;

    public $name_en;

    public $name_ar;

    public $name_ckb;

    protected function rules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_ckb' => ['required', 'string', 'max:255'],
        ];
    }
}

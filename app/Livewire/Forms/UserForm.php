<?php

namespace App\Livewire\Forms;

use App\Enums\StatusEnum;
use Livewire\Form;

class UserForm extends Form
{
    public $edit = false;

    public $user_id;

    public $name;

    public $username;

    public $phone;

    public $email;

    public $password;

    public $password_confirmation;


    public $status = StatusEnum::Active->value;


    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $this->user_id],
            'phone' => ['nullable', 'string', 'unique:users,phone,' . $this->user_id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user_id],
            'password' => $this->edit ? '' : ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'in:' . implode(',', StatusEnum::toArray())],
        ];
    }
}

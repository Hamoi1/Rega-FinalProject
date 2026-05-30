<?php

namespace App\Livewire\Forms\Auth;

use Livewire\Form;

class LoginForm extends Form
{
    public string $email;

    public string $password;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ];
    }
}

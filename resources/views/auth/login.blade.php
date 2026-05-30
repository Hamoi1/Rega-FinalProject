<?php

use App\Livewire\Forms\Auth\LoginForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Title('Login')] class extends Component {
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        if (Auth::attempt(['email' => $this->form->email, 'password' => $this->form->password])) {
            if ('admin' === auth()->user()->user_type) {
                $this->redirectRoute('dashboard.home', navigate: true);
            } else {
                $this->redirectRoute('home', navigate: true);
            }
        } else {
            $this->addError('form.email', __('auth.failed'));
        }
    }
}; ?>

<div>
    <div class="flex items-center justify-center w-dvw h-screen dark:bg-zinc-900/70 dark:text-white relative">
        <x-ui.background-dot />
        <div class="relative w-full max-w-2xl p-2 mx-auto max-lg:mx-auto">
            <div
                class="absolute bottom-0 hidden rounded-full opacity-50 md:inline-block -left-20 w-60 h-60 bg-gradient-to-br from-blue-400 to-blue-500 dark:from-blue-600 dark:to-blue-700 filter blur-3xl animate-blob -z-10">
            </div>
            <div
                class="absolute top-0 hidden rounded-full opacity-50 md:inline-block -right-20 w-60 h-60 bg-gradient-to-br from-red-400 to-red-500 dark:from-red-600 dark:to-red-700 filter blur-3xl animate-blob -z-10">
            </div>
            <a href="{{ route('home') }}" wire:navigate.hover
                class="flex items-center justify-center gap-2 break-words mb-5">
                <img src="{{ getCompanyLogo() }}" alt="company logo" loading="lazy"
                    class="object-cover rounded-full size-12 md:size-16 border border-zinc-500 dark:border-zinc-200">
                <h1 class="text-2xl font-medium text-zinc-800 md:text-4xl dark:text-white">
                    {{ setting()->get('company_name', config('app.name')) }}
                </h1>
            </a>
            <div
                class="bg-white rounded-lg shadow-lg dark:bg-zinc-900 transition duration-300 ease-in-out  p-5 md:p-7 md:py-10 space-y-7 md:space-y-10">
                <div class="space-y-1.5">
                    <h3 class=" text-lg font-medium text-zinc-800 md:text-xl dark:text-white">
                        @lang('words.welcome_back')
                    </h3>
                    <p class="text-base text-zinc-400 md:text-lg dark:text-zinc-300">
                        @lang('messages.enter_your_credentials_below')
                    </p>
                </div>
                <form class="w-full space-y-5" wire:submit='login'>
                    <div>
                        <x-wireui-input wire:model='form.email' type="email" :label="__('words.email')" :placeholder="__('words.enter_', ['attr' => __('words.email')])" />
                    </div>
                    <div>
                        <x-wireui-inputs.password wire:model='form.password' :label="__('words.password')" :placeholder="__('words.enter_', ['attr' => __('words.password')])" />
                    </div>
                    <div class="mt-3">
                        <x-wireui-button type="submit" class="w-full" primary spinner="login" md>
                            @lang('words.login')
                        </x-wireui-button>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('register') }}" wire:navigate
                            class="text-sm text-blue-600 hover:underline dark:text-white">
                            @lang('messages.dont_have_an_account')
                        </a>
                    </div>
                </form>
                <div class="flex flex-row items-center justify-between gap-3 mt-3">
                    <div class="flex items-center gap-4">
                        <x-switch-theme />

                    </div>
                    <div>
                        <x-switch-language />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

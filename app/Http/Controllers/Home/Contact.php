<?php

namespace App\Http\Controllers\Home;

use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Contact extends Component
{
    use SEOToolsTrait;

    #[Validate('required|min:2')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|min:5')]
    public $message = '';

    public $success = false;

    public function mount(): void
    {
        $this->seo()->setTitle(__('words.contact'));
        $this->seo()->setDescription(__('words.contact_description'));
        $this->seo()->opengraph()->setUrl(route('contact'));
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->twitter()->setSite('@' . setting()->get('company_name', 'Rega'));
    }

    public function submit(): void
    {
        $this->validate();

        // For now, we'll log the submission.
        // In a real app, this would send an email or save to DB.
        Log::info('Contact Form Submission:', [
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ]);

        $this->success = true;
        $this->reset(['name', 'email', 'message']);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('home.contact');
    }
}

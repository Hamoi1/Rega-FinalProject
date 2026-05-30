<?php

namespace App\Http\Controllers\Home;

use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Livewire\Component;

class Privacy extends Component
{
    use SEOToolsTrait;

    public function mount(): void
    {
        $this->seo()->setTitle(__('words.privacy_policy'));
        $this->seo()->setDescription(__('words.privacy_description'));
        $this->seo()->opengraph()->setUrl(route('privacy'));
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->twitter()->setSite('@' . setting()->get('company_name', 'Rega'));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('home.privacy');
    }
}

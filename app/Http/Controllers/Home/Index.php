<?php

namespace App\Http\Controllers\Home;

use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Livewire\Component;

class Index extends Component
{
    use SEOToolsTrait;

    public function mount(): void
    {
        $this->seo()->setTitle(__('words.home'));
        $this->seo()->setDescription(setting()->get('company_name', 'Rega') . ' - a clean public transit map for finding bus routes, exploring stops, saving favorites, and using multilingual map tools.');
        $this->seo()->opengraph()->setUrl(route('home'));
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->twitter()->setSite('@' . setting()->get('company_name', 'Rega'));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('home.index');
    }
}

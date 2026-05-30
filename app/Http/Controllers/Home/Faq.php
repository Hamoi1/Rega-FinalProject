<?php

namespace App\Http\Controllers\Home;

use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Livewire\Component;
use Livewire\WithPagination;

class Faq extends Component
{
    use WithPagination;
    use SEOToolsTrait;

    public function mount(): void
    {
        $this->seo()->setTitle(__('words.faq'));
        $this->seo()->setDescription(__('words.faq_description'));
        $this->seo()->opengraph()->setUrl(route('faq'));
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->twitter()->setSite('@' . setting()->get('company_name', 'Rega'));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $faqs = \App\Models\Faq::latest()->paginate(10);

        return view('home.faq', ['faqs' => $faqs]);
    }
}

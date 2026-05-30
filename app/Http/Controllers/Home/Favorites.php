<?php

namespace App\Http\Controllers\Home;

use App\Models\Favorite;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Favorites extends Component
{
    use SEOToolsTrait;
    use WithPagination;

    #[Layout('layouts.app')]
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $this->seo()->setTitle(__('words.favorites'));
        $this->seo()->setDescription(__('words.favorites'));
        $this->seo()->opengraph()->setUrl(route('favorites'));
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->twitter()->setSite('@' . setting()->get('company_name', 'Rega'));

        $locale = app()->getLocale();
        $userId = Auth::id();

        $favorites = DB::table('favorites')
            ->join('locations', 'locations.id', '=', 'favorites.location_id')
            ->select('locations.id as location_id')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(locations.name, "$.' . $locale . '")) as location_name')
            ->where('user_id', $userId)
            ->latest('favorites.created_at')
            ->paginate(20);

        return view('home.favorites', [
            'favorites' => $favorites,
        ]);
    }

    public function removeFavorite(int $locationId): void
    {
        $userId = Auth::id();
        if ( ! $userId || $locationId <= 0) {
            return;
        }

        /** @var Favorite|null $existing */
        $existing = Favorite::query()
            ->where('user_id', $userId)
            ->where('location_id', $locationId)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->resetPage();
        }
    }
}

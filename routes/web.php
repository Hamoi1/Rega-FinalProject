<?php

use App\Enums\StatusEnum;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckUserIsActiveMiddleware;
use App\Http\Middleware\SetUpLanguageMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware([SetUpLanguageMiddleware::class, 'throttle:web'])->group(function (): void {
    Route::get('/go-back', fn() => Auth::check() ? to_route('dashboard.home') : to_route('login'))->name('go_back');
    Route::get('/change-language/{lang}', function ($lang): Illuminate\Http\RedirectResponse {
        session()->put(
            'lang',
            in_array($lang, ['en', 'ckb', 'ar']) ? $lang : 'en',
        );

        return back();
    })->name('change-language');
    Route::get('/', App\Http\Controllers\Home\Index::class)->name('home');
    Route::get('/map', App\Http\Controllers\Home\Map::class)->name('map');
    Route::get('/stops/{location}', App\Http\Controllers\Home\Stop::class)->name('words.show');
    Route::get('/about', App\Http\Controllers\Home\About::class)->name('about');
    Route::get('/contact', App\Http\Controllers\Home\Contact::class)->name('contact');
    Route::get('/privacy-policy', App\Http\Controllers\Home\Privacy::class)->name('privacy');
    Route::get('/faq', App\Http\Controllers\Home\Faq::class)->name('faq');
    Route::middleware(['auth', CheckUserIsActiveMiddleware::class])->group(function (): void {
        Route::get('/favorites', App\Http\Controllers\Home\Favorites::class)
            ->name('favorites');
    });

    Route::middleware(['auth', CheckUserIsActiveMiddleware::class,AdminMiddleware::class])
        ->name('dashboard.')
        ->group(function (): void {
            Route::get('/dashboard', App\Http\Controllers\Dashboard\Index::class)->name('home');
            Route::get('/dashboard/users/{type?}', App\Http\Controllers\Dashboard\User\Index::class)->name('users');
            Route::get('/dashboard/cities', App\Http\Controllers\Dashboard\City\Index::class)->name('cities');
            Route::get('/dashboard/locations', App\Http\Controllers\Dashboard\Location\Index::class)->name('locations');
            Route::get('/dashboard/bus-lines', App\Http\Controllers\Dashboard\BusLine\Index::class)->name('bus-lines');
            Route::get('/dashboard/faqs', App\Http\Controllers\Dashboard\Faq\Index::class)->name('faqs');

        });
    Route::get('/auth/logout', function () {
        Auth::logout();
        return to_route('login');
    })->middleware(['auth'])->name('logout');

    // user not active
    Route::get('/user-not-active', function () {
        $user = Auth::user();
        if (StatusEnum::Active === $user->status) {
            return to_route('dashboard.home');
        }

        return view('dashboard.user.not_active_user');
    })
        ->middleware(['auth'])
        ->name('user.inactive');

    Route::middleware('guest')->group(function (): void {
        Volt::route('auth/login', 'auth.login')->name('login');
        Volt::route('auth/register', 'auth.register')->name('register');
    });
});

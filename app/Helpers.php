<?php

use Illuminate\Foundation\Vite;
use Spatie\Valuestore\Valuestore;

if ( ! function_exists('setting')) {
    function setting($path = 'settings.json'): Valuestore
    {
        return Valuestore::make(config_path($path));
    }
}


if ( ! function_exists('getCompanyLogo')) {
    function getCompanyLogo(): string
    {
        return in_array(setting()->get('company_logo'), [null, ''])
            ? asset('assets/images/logo.png')
            : asset('storage/' . setting()->get('company_logo'));
    }
}

if ( ! function_exists('GetCssFiles')) {
    function GetCssFiles(): string
    {
        return resolve(Vite::class)(['resources/css/app.css']);
    }
}

if ( ! function_exists('GetJsFiles')) {
    function GetJsFiles(): string
    {
        return resolve(Vite::class)(['resources/js/app.js']);
    }
}

<?php

namespace App\Services\WireUiComponents;

use Illuminate\Contracts\View\View;
use WireUi\Components\TextField\Currency;

class CustomInputCurrency extends Currency
{
    protected function blade(): View
    {
        return view('vendor.wireui.text-field.currency');
    }
}

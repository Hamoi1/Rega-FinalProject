<?php

namespace App\Services\WireUiComponents;

use Illuminate\Contracts\View\View;
use WireUi\Components\Select\Base;

class CustomSelectBase extends Base
{
    protected function blade(): View
    {
        return view('vendor.wireui.select.base');
    }
}

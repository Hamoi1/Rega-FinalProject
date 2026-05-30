<?php

namespace App\Traits;

use WireUi\Traits\WireUiActions;

trait WithNotification
{
    use WireUiActions;

    public function success($message, $description = null): void
    {
        $this->notification()->send([
            'title' => $message,
            'description' => $description,
            'icon' => 'success',
            'timeout' => 3000,
        ]);
    }

    public function error($message, $description = null): void
    {
        $this->notification()->send([
            'title' => $message,
            'description' => $description,
            'icon' => 'error',
            'timeout' => 3000,
        ]);
    }

    public function info($message, $description = null): void
    {
        $this->notification()->send([
            'title' => $message,
            'description' => $description,
            'icon' => 'info',
            'timeout' => 3000,
        ]);
    }

    public function warning($message, $description = null): void
    {
        $this->notification()->send([
            'title' => $message,
            'description' => $description,
            'icon' => 'warning',
            'timeout' => 3000,
        ]);
    }
}

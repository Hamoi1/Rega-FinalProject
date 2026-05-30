<?php

namespace App\Traits;

use Livewire\Attributes\Reactive;

trait DashboardFilterBy
{
    #[Reactive]
    public $custom_start_date;

    #[Reactive]
    public $custom_end_date;

    #[Reactive]
    public $filter_by;

    public function dateRange($filter_by): array
    {
        $date = now();

        return match ($filter_by) {
            'today' => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
            'this_week' => [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()],
            'last_week' => [$date->copy()->subWeek()->startOfWeek(), $date->copy()->subWeek()->endOfWeek()],
            'this_month' => [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()],
            'last_month' => [$date->copy()->subMonth()->startOfMonth(), $date->copy()->subMonth()->endOfMonth()],
            'three_months_ago' => [$date->copy()->subMonths(3)->startOfMonth(), $date->copy()->subMonths(3)->endOfMonth()],
            'six_months_ago' => [$date->copy()->subMonths(6)->startOfMonth(), $date->copy()->subMonths(6)->endOfMonth()],
            'this_year' => [$date->copy()->startOfYear(), $date->copy()->endOfYear()],
            'last_year' => [$date->copy()->subYear()->startOfYear(), $date->copy()->subYear()->endOfYear()],
            'two_years_ago' => [$date->copy()->subYears(2)->startOfYear(), $date->copy()->subYears(2)->endOfYear()],
            'custom' => [
                $this->custom_start_date
                    ? \Illuminate\Support\Facades\Date::parse($this->custom_start_date)->startOfDay()
                    : $date->copy()->startOfWeek(),
                $this->custom_end_date
                    ? \Illuminate\Support\Facades\Date::parse($this->custom_end_date)->endOfDay()
                    : $date->copy()->endOfWeek(),
            ],
            default => [$date->copy()->subYears(10), $date->copy()],
        };
    }
}

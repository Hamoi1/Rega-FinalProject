<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\StatusEnum;
use App\Models\BusLine;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\Url;
use Livewire\Component;
use Throwable;

class Index extends Component
{
    private const AVAILABLE_FILTERS = [
        'today',
        'this_week',
        'last_week',
        'this_month',
        'last_month',
        'three_months_ago',
        'six_months_ago',
        'this_year',
        'last_year',
        'two_years_ago',
        'all_time',
        'custom',
    ];

    #[Url()]
    public string $filter_by = 'today';

    #[Url(as: 'start')]
    public ?string $custom_start_date = null;

    #[Url(as: 'end')]
    public ?string $custom_end_date = null;

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('dashboard.index', $this->getDashboardData());
    }

    public function updated(string $property, mixed $value): void
    {
        if ('filter_by' === $property) {
            $this->filter_by = in_array((string) $value, self::AVAILABLE_FILTERS, true) ? (string) $value : 'today';

            if ('custom' === $this->filter_by) {
                $this->custom_start_date ??= now()->startOfWeek()->format('Y-m-d');
                $this->custom_end_date ??= now()->endOfWeek()->format('Y-m-d');
            }
        }
    }

    private function getDashboardData(): array
    {
        [$startDate, $endDate] = $this->resolveDateRange();

        $usersQuery = User::query();
        $busLinesQuery = BusLine::query();

        if ($startDate instanceof CarbonInterface && $endDate instanceof CarbonInterface) {
            $usersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $busLinesQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        return [
            'selectedFilter' => __('words.' . $this->filter_by),
            'selectedRange' => $this->selectedRangeLabel($startDate, $endDate),
            'statusCards' => [
                [
                    'title' => __('pages.users.plural'),
                    'subtitle' => __('pages.users.admins') . ' & ' . __('words.active'),
                    'count' => (clone $usersQuery)->where('user_type', 'admin')->where('status', StatusEnum::Active)->count(),
                    'iconBgColor' => 'bg-emerald-600',
                    'icon' => $this->usersIcon(),
                ],
                [
                    'title' => __('pages.users.plural'),
                    'subtitle' => __('pages.users.plural') . ' & ' . __('words.active'),
                    'count' => (clone $usersQuery)->where('user_type', 'user')->where('status', StatusEnum::Active)->count(),
                    'iconBgColor' => 'bg-green-600',
                    'icon' => $this->usersIcon(),
                ],
                [
                    'title' => __('pages.users.plural'),
                    'subtitle' => __('words.inactive'),
                    'count' => (clone $usersQuery)->where('status', StatusEnum::Inactive)->count(),
                    'iconBgColor' => 'bg-rose-600',
                    'icon' => $this->usersIcon(),
                ],
                [
                    'title' => __('pages.bus_lines.plural'),
                    'subtitle' => __('words.active'),
                    'count' => (clone $busLinesQuery)->active()->count(),
                    'iconBgColor' => 'bg-blue-600',
                    'icon' => $this->busLinesIcon(),
                ],
                [
                    'title' => __('pages.bus_lines.plural'),
                    'subtitle' => __('words.inactive'),
                    'count' => (clone $busLinesQuery)->inactive()->count(),
                    'iconBgColor' => 'bg-amber-600',
                    'icon' => $this->busLinesIcon(),
                ],
            ],
        ];
    }

    /**
     * @return array{0: CarbonInterface|null, 1: CarbonInterface|null}
     */
    private function resolveDateRange(): array
    {
        if ('all_time' === $this->filter_by) {
            return [null, null];
        }

        $date = now();

        try {
            return match ($this->filter_by) {
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
                        ? Date::parse($this->custom_start_date)->startOfDay()
                        : $date->copy()->startOfWeek(),
                    $this->custom_end_date
                        ? Date::parse($this->custom_end_date)->endOfDay()
                        : $date->copy()->endOfWeek(),
                ],
                default => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
            };
        } catch (Throwable) {
            return [$date->copy()->startOfDay(), $date->copy()->endOfDay()];
        }
    }

    private function selectedRangeLabel(?CarbonInterface $startDate, ?CarbonInterface $endDate): string
    {
        if ( ! $startDate instanceof CarbonInterface || ! $endDate instanceof CarbonInterface) {
            return __('words.all_time');
        }

        $start = $startDate->copy()->locale(app()->getLocale())->isoFormat('ll');
        $end = $endDate->copy()->locale(app()->getLocale())->isoFormat('ll');

        if ($start === $end) {
            return $start;
        }

        return sprintf('%s - %s', $start, $end);
    }

    private function usersIcon(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="size-6 text-white"><path d="M16 11C17.6569 11 19 9.65685 19 8C19 6.34315 17.6569 5 16 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M11 13C13.7614 13 16 10.7614 16 8C16 5.23858 13.7614 3 11 3C8.23858 3 6 5.23858 6 8C6 10.7614 8.23858 13 11 13Z" stroke="currentColor" stroke-width="1.5"/><path d="M4 20C4 17.7909 5.79086 16 8 16H14C16.2091 16 18 17.7909 18 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>';
    }

    private function busLinesIcon(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="size-6 text-white"><path d="M8 17H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M7 20H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M16 20H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M6 14V8C6 5.79086 7.79086 4 10 4H14C16.2091 4 18 5.79086 18 8V14M6 14H18M6 14V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V14" stroke="currentColor" stroke-width="1.5"/></svg>';
    }
}

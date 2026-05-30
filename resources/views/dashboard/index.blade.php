<div class="space-y-4">
    <!-- Loading State -->
    <div wire:loading wire:target="filter_by,custom_start_date,custom_end_date,currency,start_date,end_date,activeTab"
        class="fixed inset-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-sm">
        <div class="flex items-center justify-center h-full">
            <div class="flex flex-col items-center space-y-4">
                <div class="relative">
                    <div
                        class="w-12 h-12 border-4 border-blue-200 dark:border-blue-800 rounded-full animate-spin border-t-blue-600 dark:border-t-blue-400">
                    </div>
                    <div
                        class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-ping border-t-blue-600 dark:border-t-blue-400">
                    </div>
                </div>
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400 animate-pulse">@lang('words.loading')</p>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div
        class="rounded-2xl border border-zinc-200/80 bg-linear-to-r from-white to-blue-50/70 p-3 shadow-sm dark:border-zinc-800 dark:from-zinc-950 dark:to-zinc-900 lg:p-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-2">
                <h1
                    class="text-xl lg:text-3xl font-medium bg-linear-to-r from-zinc-900 via-blue-800 to-indigo-800 dark:from-white dark:via-blue-200 dark:to-indigo-200 bg-clip-text text-transparent">
                    @lang('pages.dashboard.single')
                </h1>
                <p class="text-zinc-600 dark:text-zinc-400 font-medium text-sm lg:text-base">
                    {{ now()->locale(app()->getLocale())->isoFormat('dddd, MMMM D, YYYY') }} •
                    {{ now()->locale(app()->getLocale())->isoFormat('LT') }}
                </p>
                <div class="flex flex-wrap items-center gap-2 text-xs lg:text-sm">
                    <span
                        class="rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 font-medium text-blue-700 dark:border-blue-900/60 dark:bg-blue-900/20 dark:text-blue-200">
                        {{ $selectedFilter }}
                    </span>
                    <span
                        class="rounded-full border border-zinc-200 bg-white/80 px-2.5 py-1 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900/60 dark:text-zinc-200">
                        {{ $selectedRange }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-3">
        <h2 class="text-lg font-medium text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="size-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                </path>
            </svg>
            @lang('pages.dashboard.sections.filters')
        </h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div>
                <x-wireui-select wire:model.live.debounce.300ms='filter_by' :label="__('words.filter_by')" :placeholder="__('words.select_', ['attr' => __('words.filter_by')])"
                    :options="[
                        ['label' => __('words.today'), 'value' => 'today'],
                        ['label' => __('words.this_week'), 'value' => 'this_week'],
                        ['label' => __('words.last_week'), 'value' => 'last_week'],
                        ['label' => __('words.this_month'), 'value' => 'this_month'],
                        ['label' => __('words.last_month'), 'value' => 'last_month'],
                        ['label' => __('words.three_months_ago'), 'value' => 'three_months_ago'],
                        ['label' => __('words.six_months_ago'), 'value' => 'six_months_ago'],
                        ['label' => __('words.this_year'), 'value' => 'this_year'],
                        ['label' => __('words.last_year'), 'value' => 'last_year'],
                        ['label' => __('words.two_years_ago'), 'value' => 'two_years_ago'],
                        ['label' => __('words.all_time'), 'value' => 'all_time'],
                        ['label' => __('words.custom'), 'value' => 'custom'],
                    ]" option-label="label" option-value="value" />
            </div>
            @if ($filter_by === 'custom')
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-wireui-datetime-picker without-timezone without-time :label="__('words.start_date')" :placeholder="__('words.select_', ['attr' => __('words.start_date')])"
                            wire:model.live.debounce.300ms="custom_start_date" />
                    </div>
                    <div>
                        <x-wireui-datetime-picker without-timezone without-time :label="__('words.end_date')" :placeholder="__('words.select_', ['attr' => __('words.end_date')])"
                            wire:model.live.debounce.300ms="custom_end_date" />
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="space-y-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-3">
            <h2 class="text-lg font-medium text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="size-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6m4 6V7m4 10v-4M5 17v-8"></path>
                </svg>
                @lang('pages.dashboard.sections.overview')
            </h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                @foreach ($statusCards as $statusCard)
                    <x-dashboard.stats-card :title="$statusCard['title']" :subtitle="$statusCard['subtitle']" :count="$statusCard['count']" :icon="$statusCard['icon']"
                        :icon-bg-color="$statusCard['iconBgColor']" />
                @endforeach
            </div>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(app()->getLocale(), ['ar', 'ckb']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('pages.auth.account_not_active') | {{ setting()->get('company_name', config('app.name')) }}</title>
    <meta name="description" content="{{ setting()->get('company_address') }}">
    <meta name="theme-color" content="#dc2626" />
    <link rel="shortcut icon" href="{{ getCompanyLogo() }}" type="image/png, image/x-icon, image/webp">

    @echo(GetCssFiles())
</head>

<body
    class="antialiased bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 dark:from-zinc-900 dark:via-red-950 dark:to-orange-950 min-h-screen">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="1"
                        class="text-red-300 dark:text-red-700" />
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#grid)" />
        </svg>
    </div>

    <!-- Main Content -->
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-2xl">
            <!-- Card -->
            <div
                class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-red-200 dark:border-red-800 overflow-hidden backdrop-blur-sm">
                <!-- Header with gradient -->
                <div class="bg-gradient-to-r from-red-500 to-orange-500 p-8 text-center relative">
                    <!-- Animated shapes -->
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="absolute -top-4 -left-4 w-24 h-24 bg-white/10 rounded-full animate-pulse"></div>
                        <div
                            class="absolute -bottom-4 -right-4 w-32 h-32 bg-white/5 rounded-full animate-pulse delay-1000">
                        </div>
                        <div
                            class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/10 rounded-full animate-pulse delay-500">
                        </div>
                    </div>

                    <!-- Icon container -->
                    <div class="relative mb-6">
                        <div
                            class="w-20 h-20 sm:w-24 sm:h-24 mx-auto bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white animate-bounce" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-medium text-white mb-2">
                        @lang('pages.auth.account_not_active')
                    </h1>
                    <div class="w-24 h-1 bg-white/30 rounded-full mx-auto"></div>
                </div>

                <!-- Content -->
                <div class="p-8 sm:p-12">
                    <!-- Status indicator -->
                    <div class="flex items-center justify-center mb-8">
                        <div
                            class="flex items-center gap-3 px-4 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-full">
                            <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-red-700 dark:text-red-300">Account Suspended</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="text-center mb-8">
                        <p class="text-lg sm:text-xl text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            @lang('pages.auth.account_not_active_description')
                        </p>
                    </div>

                    <!-- Info cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <div
                            class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-zinc-900 dark:text-white">Contact Support</h3>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Reach out for assistance</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-zinc-900 dark:text-white">Temporary Status</h3>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Account will be reviewed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('logout') }}"
                            class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-red-500/20">
                            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            @lang('words.logout')
                        </a>
                        <button type="button" onclick="window.location.reload()"
                            class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-white dark:bg-zinc-800 border-2 border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium rounded-xl transition-all duration-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:border-zinc-400 dark:hover:border-zinc-500 focus:outline-none focus:ring-4 focus:ring-zinc-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            @lang('words.refresh')
                        </button>
                    </div>
                </div>
            </div>

            <!-- Company info -->
            <div class="text-center mt-8">
                <div class="flex items-center justify-center gap-3 mb-2">
                    <img src="{{ getCompanyLogo() }}"
                        alt="{{ setting()->get('company_name', config('app.name')) }} Logo"
                        class="w-8 h-8 rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <span class="text-lg font-medium text-zinc-700 dark:text-zinc-300">
                        {{ setting()->get('company_name', config('app.name')) }}
                    </span>
                </div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ setting()->get('company_address', 'Company Address') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Floating elements -->
    <div class="fixed top-10 left-10 w-20 h-20 bg-red-200/20 dark:bg-red-800/20 rounded-full animate-pulse delay-1000">
    </div>
    <div
        class="fixed bottom-10 right-10 w-16 h-16 bg-orange-200/20 dark:bg-orange-800/20 rounded-full animate-pulse delay-2000">
    </div>
    <div
        class="fixed top-1/2 right-20 w-12 h-12 bg-yellow-200/20 dark:bg-yellow-800/20 rounded-full animate-pulse delay-3000">
    </div>
</body>

</html>

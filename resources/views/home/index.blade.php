<div class="overflow-x-hidden bg-zinc-50 dark:bg-zinc-950 font-sans selection:bg-blue-100 selection:text-blue-900">
    {{-- Hero Section --}}
    <section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden pt-32">
        {{-- Background Elements --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div
                class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] dark:bg-[radial-gradient(#27272a_1px,transparent_1px)] [background-size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]">
            </div>
            {{-- Animated Blobs --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] opacity-30 dark:opacity-20">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-blob">
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-blob animation-delay-2000 ml-20 mt-20">
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-blob animation-delay-4000 -ml-20 -mt-20">
                </div>
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-zinc-50 via-transparent to-transparent dark:from-zinc-950">
            </div>
        </div>
        <div class="relative z-10 px-6 mx-auto max-w-7xl lg:px-8 flex flex-col items-center text-center">
            {{-- Status Badge --}}
            <div
                class="mb-8 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 dark:bg-zinc-900/80 border border-zinc-200 dark:border-zinc-800 backdrop-blur-sm shadow-sm animate-fade-in-up">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span
                    class="text-xs font-bold text-zinc-600 dark:text-zinc-300 tracking-wide uppercase">{{ __('words.live_system_active') }}</span>
            </div>

            {{-- Main Heading --}}
            <h1
                class="w-full max-w-6xl text-4xl font-black tracking-tight text-zinc-900 dark:text-white sm:text-7xl lg:text-8xl mb-8 animate-fade-in-up delay-100 leading-[0.9]">
                {{ __('words.home_title') }}
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-600 dark:from-blue-400 dark:via-indigo-300 dark:to-blue-400 bg-300% animate-gradient">
                    {{ __('words.smart') }}
                </span>
            </h1>

            <p
                class="max-w-2xl mx-auto text-lg sm:text-xl text-zinc-600 dark:text-zinc-400 animate-fade-in-up delay-200 leading-relaxed font-medium">
                {{ __('words.home_description') }}
            </p>

            {{-- Buttons --}}
            <div
                class="flex flex-col sm:flex-row items-center gap-4 mt-12 animate-fade-in-up delay-300 w-full sm:w-auto">
                <a href="{{ route('map') }}"
                    class="w-full sm:w-auto group relative px-8 py-4 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-full font-bold text-lg shadow-xl shadow-zinc-900/20 dark:shadow-white/10 hover:scale-105 transition-all overflow-hidden">
                    <div
                        class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 dark:via-black/10 to-transparent -translate-x-full group-hover:animate-shimmer">
                    </div>
                    <span class="relative flex items-center justify-center gap-2">
                        {{ __('words.view_live_map') }}
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1 rtl:rotate-180" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </span>
                </a>
                <a href="{{ route('about') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white border border-zinc-200 dark:border-zinc-700 rounded-full font-bold text-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600 transition-all hover:scale-105 shadow-sm">
                    {{ __('words.learn_more') }}
                </a>
            </div>

            {{-- Mockup --}}
            <div class="my-20 relative w-full max-w-6xl animate-fade-in-up delay-500 perspective-1000">
                <div
                    class="relative rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-xl shadow-2xl overflow-hidden w-full  h-100 md:h-120 xl:h-200 group transform transition-transform duration-700 hover:rotate-x-2">
                    <x-ui.map id="home-map" :zoom-level="14" :disable-mark-action="true" :has-multiple="true" />
                </div>

                <!-- Floating Elements -->
                <div
                    class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl shadow-2xl -rotate-6 animate-float hidden md:flex items-center justify-center z-20">
                    <div class="text-white text-center">
                        <p class="text-3xl font-black">{{ __('words.map') }}</p>
                        <p class="text-xs font-bold uppercase opacity-80">{{ __('words.stops_digitized') }}</p>
                    </div>
                </div>

                <div
                    class="absolute -bottom-10 -left-10 w-48 h-24 bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl rotate-3 animate-float animation-delay-2000 hidden md:flex items-center gap-4 px-6 z-20 border border-zinc-100 dark:border-zinc-700">
                    <div
                        class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-zinc-900 dark:text-white">{{ __('words.route_found') }}</p>
                        <p class="text-xs text-zinc-500">{{ __('words.less_than_min') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-32 relative overflow-hidden bg-zinc-50 dark:bg-zinc-950">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div
                class="absolute top-1/2 left-0 w-[500px] h-[500px] bg-blue-500/5 rounded-full blur-3xl -translate-y-1/2 -translate-x-1/2">
            </div>
            <div
                class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
            </div>
        </div>
        {{-- Features Section --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 space-y-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $features = [
                        [
                            'icon' => 'map',
                            'title' => 'words.geojson_routes',
                            'desc' => 'words.geojson_routes_desc',
                            'color' => 'blue',
                        ],
                        [
                            'icon' => 'map-pin',
                            'title' => 'words.mapped_stops',
                            'desc' => 'words.mapped_stops_desc',
                            'color' => 'yellow',
                        ],
                        [
                            'icon' => 'language',
                            'title' => 'words.multilingual_access',
                            'desc' => 'words.multilingual_access_desc',
                            'color' => 'green',
                        ],
                    ];
                @endphp
                @foreach ($features as $feature)
                    <div
                        class="group relative bg-white/60 dark:bg-[#18181b]/60 backdrop-blur-xl rounded-[2rem] p-8 sm:p-10 border border-white/40 dark:border-zinc-800/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] dark:hover:shadow-[0_8px_30px_rgb(0,0,0,0.2)] transition-all duration-300 animate-fade-in-up delay-{{ $loop->iteration * 100 }}">
                        <div class="relative z-10">
                            {{-- Icon Container --}}
                            <div
                                class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white/80 dark:bg-[#202024]/80 backdrop-blur-md border border-white/50 dark:border-zinc-700/50 shadow-sm flex items-center justify-center text-zinc-600 dark:text-zinc-400 mb-6 sm:mb-8">
                                <x-wireui-icon name="{{ $feature['icon'] }}" class="w-5 h-5 sm:w-6 sm:h-6" />
                            </div>

                            <h3 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white mb-3 sm:mb-4">
                                {{ __($feature['title']) }}
                            </h3>

                            <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed text-sm">
                                {{ __($feature['desc']) }}
                            </p>

                            {{-- Decorative Line --}}
                            <div
                                class="w-10 h-0.5 bg-zinc-200/80 dark:bg-zinc-800/80 rounded-full mt-6 sm:mt-8 group-hover:bg-{{ $feature['color'] }}-500/50 transition-colors duration-300">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- How It Works Section --}}
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                {{-- Left Content --}}
                <div class="space-y-12">
                    <div class="space-y-6 animate-fade-in-up">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/30">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            <span
                                class="text-xs font-bold uppercase tracking-widest">{{ __('words.trip_steps') }}</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-black text-zinc-900 dark:text-white leading-tight">
                            {{ __('words.making_city_travel_simple') }}
                        </h2>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400">
                            {{ __('words.core_values_desc') }}
                        </p>
                    </div>

                    <div class="space-y-8">
                        @foreach ([['step' => '01', 'title' => 'words.explore', 'desc' => 'words.try_searching_another_location', 'icon' => 'magnifying-glass'], ['step' => '02', 'title' => 'words.route_finder', 'desc' => 'words.fast_reliable_desc', 'icon' => 'map-pin'], ['step' => '03', 'title' => 'words.ready_to_ride', 'desc' => 'words.join_commuters_trust', 'icon' => 'check-circle']] as $step)
                            <div class="flex gap-6 group animate-fade-in-up delay-{{ $loop->iteration * 100 }}">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-12 h-12 shrink-0 rounded-full bg-zinc-100 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-zinc-900 dark:text-white font-bold shadow-sm group-hover:border-blue-500 group-hover:text-blue-500 transition-colors">
                                        {{ $step['step'] }}
                                    </div>
                                    @if (!$loop->last)
                                        <div
                                            class="w-px h-full bg-zinc-200 dark:bg-zinc-800 my-2 group-hover:bg-blue-500/30 transition-colors">
                                        </div>
                                    @endif
                                </div>
                                <div class="pb-8">
                                    <h3
                                        class="text-xl font-bold text-zinc-900 dark:text-white mb-2 flex items-center gap-2">
                                        {{ __($step['title']) }}
                                    </h3>
                                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                        {{ str_replace(':company', config('app.name'), __($step['desc'])) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Right Image/Mockup --}}
                <div
                    class="relative animate-fade-in-up delay-300 lg:h-[700px] rounded-[2.5rem] overflow-hidden shadow-2xl group">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-blue-600/20 to-indigo-600/20 mix-blend-overlay z-10 group-hover:opacity-0 transition-opacity duration-700">
                    </div>
                    <img src="https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1000&auto=format&fit=crop"
                        alt="Public Transit"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">

                    {{-- Floating Stats Card --}}
                    <div
                        class="absolute bottom-8 left-8 right-8 bg-zinc-900/95 backdrop-blur-md p-6 rounded-2xl border border-zinc-800 shadow-2xl z-20 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-2">
                                    {{ __('words.systems_operational') }}</p>
                                <p class="text-3xl font-black text-white">{{ __('words.live_system_active') }}</p>
                            </div>
                            <div class="w-14 h-14 rounded-full bg-emerald-500/10 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                                    <div class="w-4 h-4 rounded-full bg-emerald-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-zinc-900 dark:bg-black"></div>
        <div
            class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1449824913935-59a10b8d2000?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-20 mix-blend-luminosity">
        </div>
        <div
            class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-900/80 to-transparent dark:from-black dark:via-black/80">
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-6xl font-black text-white mb-8 animate-fade-in-up">
                {{ __('words.ready_to_ride') }}
            </h2>
            <p class="text-xl text-zinc-300 mb-12 animate-fade-in-up delay-100">
                {{ str_replace(':company', config('app.name'), __('words.join_commuters_trust')) }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up delay-200">
                <a href="{{ route('map') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-full font-bold text-lg transition-all shadow-lg shadow-blue-600/30 hover:scale-105">
                    {{ __('words.view_live_map') }}
                </a>
                <a href="{{ route('contact') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-full font-bold text-lg backdrop-blur-sm transition-all hover:scale-105">
                    {{ __('words.contact_us') }}
                </a>
            </div>
        </div>
    </section>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(-6deg);
            }

            50% {
                transform: translateY(-10px) rotate(-6deg);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .animate-shimmer {
            animation: shimmer 2s infinite;
        }

        .bg-300% {
            background-size: 300% 300%;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animate-gradient {
            animation: gradient 6s ease infinite;
        }

        @keyframes progress {
            0% {
                width: 0%;
            }

            100% {
                width: 100%;
            }
        }

        .animate-progress {
            animation: progress 2s ease-in-out infinite;
        }
    </style>

</div>

<div
    class="relative bg-zinc-50 dark:bg-zinc-950 min-h-screen font-sans overflow-hidden pt-32 pb-20 selection:bg-blue-100 selection:text-blue-900">

    {{-- Background Decoration --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div
            class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] dark:bg-[radial-gradient(#27272a_1px,transparent_1px)] [background-size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]">
        </div>
        {{-- Animated Blobs --}}
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] opacity-30 dark:opacity-20 pointer-events-none">
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
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-50 via-transparent to-transparent dark:from-zinc-950">
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">

        {{-- Hero Section --}}
        <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center mb-32">
            <div class="space-y-8 animate-fade-in-up">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 dark:bg-zinc-900/80 border border-zinc-200 dark:border-zinc-800 backdrop-blur-sm shadow-sm animate-fade-in-up">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    <span
                        class="text-xs font-bold text-zinc-600 dark:text-zinc-300 tracking-wide uppercase">{{ __('words.about_us') }}</span>
                </div>

                <h1 class="text-5xl lg:text-7xl font-black text-zinc-900 dark:text-white leading-[0.9] tracking-tight">
                    {{ __('words.making_city_travel_simple') }}
                </h1>

                <p
                    class="max-w-2xl text-lg sm:text-xl text-zinc-600 dark:text-zinc-400 animate-fade-in-up delay-200 leading-relaxed font-medium">
                    {{ __('words.about_description') }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 pt-4 animate-fade-in-up delay-300">
                    <a href="{{ route('contact') }}"
                        class="group relative px-8 py-4 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-full font-bold text-lg shadow-xl shadow-zinc-900/20 dark:shadow-white/10 hover:scale-105 transition-all overflow-hidden text-center">
                        <div
                            class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 dark:via-black/10 to-transparent -translate-x-full group-hover:animate-shimmer">
                        </div>
                        <span class="relative flex items-center justify-center gap-2">
                            {{ __('words.get_in_touch') }}
                        </span>
                    </a>
                    <a href="{{ route('map') }}"
                        class="px-8 py-4 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white border border-zinc-200 dark:border-zinc-700 rounded-full font-bold text-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-all hover:scale-105 shadow-sm text-center">
                        {{ __('words.live_map') }}
                    </a>
                </div>
            </div>

            <div
                class="relative lg:h-[600px] rounded-[2.5rem] overflow-hidden shadow-2xl animate-fade-in-up delay-500 group perspective-1000 transform transition-transform duration-700 hover:rotate-x-2">
                <img src="https://images.unsplash.com/photo-1570125909232-eb263c188f7e?q=80&w=2071&auto=format&fit=crop"
                    alt="Public Transport"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                <div class="absolute bottom-8 left-8 right-8 text-white z-20">
                    <p class="text-sm font-bold uppercase tracking-widest opacity-80 mb-2">{{ __('words.established') }}
                    </p>
                    <p
                        class="text-5xl font-black bg-clip-text text-transparent bg-gradient-to-r from-white to-white/70">
                        {{ setting()->get('company_start_year', '2026') }}</p>
                </div>

                {{-- Company Logo --}}
                <div
                    class="absolute top-8 left-8 size-20 rounded-2xl bg-white/80 dark:bg-[#202024]/80 backdrop-blur-md border border-white/50 dark:border-zinc-700/50 flex items-center justify-center shadow-sm">
                    <a href="{{ route('home') }}" wire:navigate class="group relative z-50 flex items-center gap-3">
                        <img src="{{ getCompanyLogo() }}" alt="{{ setting()->get('company_name') }}"
                            class="size-18 w-auto transition duration-300 group-hover:scale-105">
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats Section --}}
        <div
            class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-32 border border-zinc-200/50 dark:border-zinc-800/50 py-16 backdrop-blur-xl bg-white/60 dark:bg-zinc-900/60 rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] animate-fade-in-up delay-300">
            @foreach ([['value' => 'words.open_access_value', 'label' => 'words.users_stat', 'color' => 'blue'], ['value' => 'words.clear_routes_value', 'label' => 'words.routes_stat', 'color' => 'indigo'], ['value' => '3', 'label' => 'words.languages_stat', 'color' => 'cyan'], ['value' => 'words.saved_favorites_value', 'label' => 'words.route_success_stat', 'color' => 'purple']] as $stat)
                <div class="text-center space-y-3 group cursor-default">
                    <p
                        class="text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-zinc-900 to-zinc-600 dark:from-white dark:to-zinc-400 transition-all duration-300 transform group-hover:scale-110 group-hover:from-{{ $stat['color'] }}-500 group-hover:to-{{ $stat['color'] }}-400">
                        {{ str_starts_with($stat['value'], 'words.') ? __($stat['value']) : $stat['value'] }}
                    </p>
                    <p
                        class="text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 group-hover:text-{{ $stat['color'] }}-500 dark:group-hover:text-{{ $stat['color'] }}-400 transition-colors">
                        {{ __($stat['label']) }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Values Section --}}
        <div class="mb-32 relative">
            <div
                class="absolute top-1/2 left-0 w-[500px] h-[500px] bg-blue-500/5 rounded-full blur-3xl -translate-y-1/2 -translate-x-1/2 pointer-events-none opacity-50">
            </div>
            <div
                class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none opacity-50">
            </div>

            <div class="text-center max-w-3xl mx-auto mb-16 animate-fade-in-up delay-200">
                <h2 class="text-4xl md:text-5xl font-black text-zinc-900 dark:text-white mb-6 tracking-tight">
                    {{ __('words.our_core_values') }}
                </h2>
                <p class="text-xl text-zinc-600 dark:text-zinc-400 font-medium leading-relaxed">
                    {{ __('words.core_values_desc') }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative z-10">
                @foreach ([['icon' => 'bolt', 'title' => 'words.our_mission', 'desc' => 'words.mission_desc', 'color' => 'blue'], ['icon' => 'shield-check', 'title' => 'words.reliable', 'desc' => 'words.reliable_desc', 'color' => 'indigo'], ['icon' => 'users', 'title' => 'words.accessible', 'desc' => 'words.accessible_desc', 'color' => 'cyan']] as $value)
                    <div
                        class="group relative bg-white/60 dark:bg-[#18181b]/60 backdrop-blur-xl border border-white/40 dark:border-zinc-800/50 p-10 rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] dark:hover:shadow-[0_8px_30px_rgb(0,0,0,0.2)] transition-all duration-300 animate-fade-in-up delay-[{{ $loop->iteration * 100 }}ms]">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-{{ $value['color'] }}-50 to-transparent dark:from-{{ $value['color'] }}-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2.5rem]">
                        </div>

                        <div class="relative z-10">
                            <div
                                class="w-16 h-16 rounded-2xl bg-white/80 dark:bg-[#202024]/80 backdrop-blur-md border border-white/50 dark:border-zinc-700/50 flex items-center justify-center text-{{ $value['color'] }}-600 dark:text-{{ $value['color'] }}-400 mb-8 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                                <x-app-icon name="{{ $value['icon'] }}" class="w-8 h-8" />
                            </div>
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">{{ __($value['title']) }}
                            </h3>
                            <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed font-medium">
                                {{ __($value['desc']) }}</p>

                            {{-- Decorative Line --}}
                            <div
                                class="w-12 h-1 bg-zinc-200/80 dark:bg-zinc-800/80 rounded-full mt-8 group-hover:bg-{{ $value['color'] }}-500 transition-colors duration-300">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- CTA Block --}}
        <div
            class="relative rounded-[3rem] overflow-hidden bg-zinc-900 dark:bg-black text-white px-6 py-24 text-center border border-zinc-800 shadow-2xl animate-fade-in-up delay-500">
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1449824913935-59a10b8d2000?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-20 mix-blend-luminosity">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-900/80 to-transparent dark:from-black dark:via-black/80">
            </div>

            <div
                class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl animate-blob pointer-events-none">
            </div>
            <div
                class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/30 rounded-full blur-3xl animate-blob animation-delay-2000 pointer-events-none">
            </div>

            <div class="relative z-10 max-w-3xl mx-auto space-y-8">
                <h2 class="text-4xl md:text-6xl font-black tracking-tight">{{ __('words.ready_to_ride') }}</h2>
                <p class="text-xl text-zinc-300 font-medium">
                    {{ __('words.join_commuters_trust', ['company' => setting()->get('company_name', 'Rega')]) }}
                </p>
                <a href="{{ route('login') }}"
                    class="inline-block mt-8 px-10 py-5 bg-white text-zinc-900 rounded-full font-bold text-lg hover:scale-105 transition-all shadow-[0_0_40px_rgba(255,255,255,0.3)] hover:shadow-[0_0_60px_rgba(255,255,255,0.5)]">
                    {{ __('words.create_account') }}
                </a>
            </div>
        </div>

    </div>

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

        .delay-[100ms],
        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-[200ms],
        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-[300ms],
        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-[400ms],
        .delay-400 {
            animation-delay: 0.4s;
        }

        .delay-[500ms],
        .delay-500 {
            animation-delay: 0.5s;
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .animate-shimmer {
            animation: shimmer 2s infinite;
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

        .bg-300\% {
            background-size: 300% 300%;
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
    </style>
</div>

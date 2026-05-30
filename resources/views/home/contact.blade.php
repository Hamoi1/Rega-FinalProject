<div
    class="relative min-h-screen bg-zinc-50 dark:bg-zinc-950 pt-32 pb-20 overflow-hidden font-sans selection:bg-blue-100 selection:text-blue-900">

    {{-- Background Elements --}}
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
                class="absolute inset-0 bg-gradient-to-r from-purple-400 to-blue-400 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-blob animation-delay-2000 ml-20 mt-20">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-cyan-500 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-blob animation-delay-4000 -ml-20 -mt-20">
            </div>
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-50 via-transparent to-transparent dark:from-zinc-950">
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-24 items-start">

            {{-- Contact Info Side --}}
            <div class="space-y-12 lg:sticky lg:top-32 animate-fade-in-up">
                <div>
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 dark:bg-zinc-900/80 border border-zinc-200 dark:border-zinc-800 backdrop-blur-sm shadow-sm mb-8 animate-fade-in-up">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        <span
                            class="text-xs font-bold uppercase tracking-widest text-zinc-600 dark:text-zinc-300">{{ __('words.contact_us') }}</span>
                    </div>

                    <h1
                        class="text-5xl lg:text-7xl font-black text-zinc-900 dark:text-white leading-[0.9] tracking-tight mb-6 animate-fade-in-up delay-100">
                        {{ __('words.lets_start_conversation') }}
                    </h1>

                    <p
                        class="text-xl text-zinc-600 dark:text-zinc-400 leading-relaxed font-medium animate-fade-in-up delay-200">
                        {{ __('words.contact_intro') }} {{ __('words.contact_intro_short') }}
                    </p>
                </div>

                <div class="space-y-6">
                    @php
                        $contactCards = [
                            [
                                'icon' => 'map-pin',
                                'title' => 'words.address',
                                'value' => nl2br(
                                    e(setting()->get('company_address', 'Rega Transit Support')),
                                ),
                                'color' => 'blue',
                                'type' => 'text',
                            ],
                            [
                                'icon' => 'phone',
                                'title' => 'words.phone',
                                'value' => is_array(setting()->get('company_phone'))
                                    ? setting()->get('company_phone')[0] ?? ''
                                    : setting()->get('company_phone'),
                                'link' =>
                                    'tel:' .
                                    (is_array(setting()->get('company_phone'))
                                        ? setting()->get('company_phone')[0] ?? ''
                                        : setting()->get('company_phone')),
                                'color' => 'indigo',
                                'type' => 'link',
                            ],
                            [
                                'icon' => 'mail',
                                'title' => 'words.email_address',
                                'value' => setting()->get('company_email'),
                                'link' => 'mailto:' . setting()->get('company_email'),
                                'color' => 'purple',
                                'type' => 'link',
                            ],
                        ];
                    @endphp

                    @foreach ($contactCards as $card)
                        <div
                            class="flex items-start gap-6 group p-6 rounded-3xl bg-white/60 dark:bg-[#18181b]/60 backdrop-blur-xl border border-white/40 dark:border-zinc-800/50 hover:border-{{ $card['color'] }}-300 dark:hover:border-{{ $card['color'] }}-800/50 transition-all shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] animate-fade-in-up md:delay-{{ 300 + $loop->index * 100 }}">
                            <div
                                class="w-14 h-14 rounded-2xl bg-white/80 dark:bg-[#202024]/80 backdrop-blur-md border border-white/50 dark:border-zinc-700/50 flex items-center justify-center text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400 shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-transform shadow-sm">
                                <x-app-icon name="{{ $card['icon'] }}" class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">
                                    {{ __($card['title']) }}</h3>
                                @if ($card['type'] === 'link')
                                    <a href="{{ $card['link'] }}"
                                        class="text-zinc-600 dark:text-zinc-400 hover:text-{{ $card['color'] }}-600 dark:hover:text-{{ $card['color'] }}-400 transition-colors font-medium">
                                        {{ $card['value'] }}
                                    </a>
                                @else
                                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed font-medium">
                                        {!! $card['value'] !!}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-8 border-t border-zinc-200/50 dark:border-zinc-800/50 animate-fade-in-up delay-[500ms]">
                    <p class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-6">
                        {{ __('words.follow_us') }}</p>
                    <div class="flex gap-4">
                        @foreach (['facebook', 'twitter', 'instagram', 'linkedin'] as $social)
                            @if (setting()->get($social))
                                <a href="{{ setting()->get($social) }}" target="_blank"
                                    class="w-12 h-12 rounded-full bg-white/60 dark:bg-[#18181b]/60 backdrop-blur-xl border border-white/40 dark:border-zinc-800/50 flex items-center justify-center text-zinc-500 dark:text-zinc-400 hover:text-white hover:bg-blue-600 hover:border-blue-600 dark:hover:bg-blue-600 dark:hover:text-white transition-all shadow-sm hover:shadow-lg hover:-translate-y-1">
                                    <span class="sr-only">{{ ucfirst($social) }}</span>
                                    <x-app-icon :name="$social" class="w-5 h-5" />
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Contact Form Side --}}
            <div class="relative animate-fade-in-up delay-300 transform perspective-1000 mt-12 lg:mt-0">
                <div
                    class="relative bg-white/60 dark:bg-[#18181b]/60 backdrop-blur-2xl rounded-[2.5rem] p-8 md:p-12 shadow-[0_8px_40px_rgb(0,0,0,0.08)] dark:shadow-[0_8px_40px_rgb(0,0,0,0.4)] border border-white/50 dark:border-zinc-800/50 overflow-hidden transform transition-all duration-500 hover:shadow-[0_16px_60px_rgb(0,0,0,0.1)] dark:hover:shadow-[0_16px_60px_rgb(0,0,0,0.5)]">
                    {{-- Decorative Blobs inside form --}}
                    <div
                        class="absolute -top-20 -right-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none animate-blob">
                    </div>
                    <div
                        class="absolute -bottom-20 -left-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none animate-blob animation-delay-2000">
                    </div>

                    <h2 class="text-3xl font-black text-zinc-900 dark:text-white mb-2 tracking-tight">
                        {{ __('words.send_us_message_title') }}
                    </h2>
                    <form wire:submit="submit" class="space-y-6 relative z-10">
                        @if ($success)
                            <div x-data="{ show: true }" x-show="show" x-transition
                                class="p-4 rounded-2xl bg-emerald-50/80 dark:bg-emerald-900/20 backdrop-blur-md border border-emerald-200 dark:border-emerald-900/30 flex items-start gap-3 shadow-sm animate-fade-in-up">
                                <div class="text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">
                                    <x-app-icon name="check-circle" class="w-5 h-5" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-emerald-800 dark:text-emerald-400">
                                        {{ __('words.message_sent') }}</h3>
                                    <p class="text-xs text-emerald-600 dark:text-emerald-500 mt-1 font-medium">
                                        {{ __('words.message_sent_desc') }}</p>
                                </div>
                                <button type="button" @click="show = false"
                                    class="ml-auto text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                                    <x-app-icon name="x-mark" class="w-4 h-4" />
                                </button>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <div class="relative group">
                                <label for="name"
                                    class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2 ml-1">{{ __('words.your_name') }}</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-zinc-400 group-focus-within:text-blue-500 transition-colors">
                                        <x-app-icon name="user" class="w-5 h-5" />
                                    </div>
                                    <input type="text" wire:model="name" id="name"
                                        class="w-full pl-12 pr-6 py-4 rounded-2xl bg-white/50 dark:bg-zinc-900/50 backdrop-blur-xl border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium placeholder:text-zinc-400 shadow-inner group-hover:border-blue-200 dark:group-hover:border-blue-900/50"
                                        placeholder="John Doe">
                                </div>
                                @error('name')
                                    <span
                                        class="text-xs text-red-500 mt-2 ml-2 font-bold flex items-center gap-1 animate-fade-in-up text-left"><x-app-icon
                                            name="exclamation-circle" class="w-3 h-3" /> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="relative group">
                                <label for="email"
                                    class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2 ml-1">{{ __('words.your_email') }}</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-zinc-400 group-focus-within:text-indigo-500 transition-colors">
                                        <x-app-icon name="envelope" class="w-5 h-5" />
                                    </div>
                                    <input type="email" wire:model="email" id="email"
                                        class="w-full pl-12 pr-6 py-4 rounded-2xl bg-white/50 dark:bg-zinc-900/50 backdrop-blur-xl border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium placeholder:text-zinc-400 shadow-inner group-hover:border-indigo-200 dark:group-hover:border-indigo-900/50"
                                        placeholder="john@example.com">
                                </div>
                                @error('email')
                                    <span
                                        class="text-xs text-red-500 mt-2 ml-2 font-bold flex items-center gap-1 animate-fade-in-up text-left"><x-app-icon
                                            name="exclamation-circle" class="w-3 h-3" /> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="relative group">
                                <label for="message"
                                    class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2 ml-1">{{ __('words.your_message') }}</label>
                                <textarea wire:model="message" id="message" rows="5"
                                    class="w-full px-6 py-4 rounded-2xl bg-white/50 dark:bg-zinc-900/50 backdrop-blur-xl border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all resize-none font-medium placeholder:text-zinc-400 shadow-inner group-hover:border-blue-200 dark:group-hover:border-blue-900/50"
                                    placeholder="{{ __('words.how_can_we_help') }}"></textarea>
                                @error('message')
                                    <span
                                        class="text-xs text-red-500 mt-2 ml-2 font-bold flex items-center gap-1 animate-fade-in-up text-left"><x-app-icon
                                            name="exclamation-circle" class="w-3 h-3" /> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit"
                                class="w-full relative px-8 py-4 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold rounded-2xl shadow-xl shadow-zinc-900/20 dark:shadow-white/10 transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 group overflow-hidden">
                                <div
                                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 dark:via-black/10 to-transparent -translate-x-full group-hover:animate-shimmer">
                                </div>
                                <span class="relative flex items-center gap-2" wire:loading.remove>
                                    {{ __('words.send_message') }}
                                    <x-app-icon name="paper-airplane"
                                        class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                                </span>
                                <span wire:loading>
                                    <span class="relative flex items-center gap-2">
                                        <x-app-icon name="spinner" class="animate-spin h-5 w-5" />
                                        {{ __('words.sending') }}
                                    </span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
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

        .delay-100,
        .md\:delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200,
        .md\:delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300,
        .md\:delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400,
        .md\:delay-400 {
            animation-delay: 0.4s;
        }

        .delay-500,
        .delay-\[500ms\] {
            animation-delay: 0.5s;
        }

        .md\:delay-600 {
            animation-delay: 0.6s;
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .animate-shimmer {
            animation: shimmer 2s infinite;
        }
    </style>
</div>

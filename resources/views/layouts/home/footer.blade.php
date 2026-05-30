<footer
    class="relative bg-zinc-50 dark:bg-zinc-950 pt-24 pb-12 border-t border-zinc-200 dark:border-zinc-900 overflow-hidden">
    {{-- Decorative Background --}}
    <div class="absolute inset-0 z-0 pointer-events-none opacity-40 dark:opacity-20">
        <div
            class="absolute inset-0 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]">
        </div>
    </div>

    <div class="relative z-10 w-full mx-auto max-w-[calc(100%-4rem)] px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-16 mb-20">

            {{-- Brand Column --}}
            <div class="md:col-span-4 lg:col-span-5 space-y-8">
                <a href="{{ route('home') }}" wire:navigate class="inline-block group">
                    <img class="h-12 w-auto grayscale group-hover:grayscale-0 transition-all duration-300"
                        src="{{ getCompanyLogo() }}" alt="{{ setting()->get('company_name') }}">
                </a>

                <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-sm">
                    {{ setting()->get('about_of_company', 'Empowering urban mobility with real-time tracking and smart routing solutions.') }}
                </p>

                <div class="flex gap-4">
                    @foreach (['facebook', 'twitter', 'instagram', 'linkedin'] as $social)
                        @if (setting()->get($social))
                            <a href="{{ setting()->get($social) }}" target="_blank"
                                class="w-10 h-10 rounded-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 flex items-center justify-center text-zinc-500 hover:text-white hover:bg-blue-600 hover:border-blue-600 dark:hover:bg-blue-600 transition-all duration-300 shadow-sm">
                                <span class="sr-only">{{ ucfirst($social) }}</span>
                                <x-app-icon :name="$social" class="w-4 h-4" />
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Links Columns --}}
            <div class="md:col-span-8 lg:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-6">
                        {{ __('words.explore') }}</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('home') }}" wire:navigate
                                class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">{{ __('words.home') }}</a>
                        </li>
                        <li><a href="{{ route('map') }}" wire:navigate
                                class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">{{ __('words.live_map') }}</a>
                        </li>
                        <li><a href="{{ route('about') }}" wire:navigate
                                class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">{{ __('words.about_us') }}</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-6">
                        {{ __('words.support') }}</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('contact') }}" wire:navigate
                                class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">{{ __('words.contact_us') }}</a>
                        </li>
                        <li><a href="{{ route('faq') }}" wire:navigate
                                class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">{{ __('words.faq') }}</a>
                        </li>
                        <li><a href="{{ url()->current() }}"
                                class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">{{ __('words.help_center') }}</a>
                        </li>
                    </ul>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-6">
                        {{ __('words.legal') }}</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('privacy') }}" wire:navigate
                                class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">{{ __('words.privacy_policy') }}</a>
                        </li>
                        <li><a href="{{ url()->current() }}"
                                class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">{{ __('words.terms_of_service') }}</a>
                        </li>
                        <li><a href="{{ url()->current() }}"
                                class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">{{ __('words.cookies') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div
            class="pt-8 border-t border-zinc-200 dark:border-zinc-800 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-sm text-zinc-500 font-medium text-center md:text-left">
                &copy; {{ date('Y') }} {{ setting()->get('company_name', 'Rega') }}.
                {{ __('words.all_rights_reserved') }}
            </p>

            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2 text-sm text-zinc-500 font-medium">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    {{ __('words.systems_operational') }}
                </span>
            </div>
        </div>
    </div>
</footer>

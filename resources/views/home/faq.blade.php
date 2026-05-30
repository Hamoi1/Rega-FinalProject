<div class="relative min-h-screen bg-zinc-50 dark:bg-zinc-950 pt-32 pb-20 overflow-hidden font-sans">

    {{-- Background Decoration --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div
            class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] dark:bg-[radial-gradient(#27272a_1px,transparent_1px)] [background-size:24px_24px] opacity-70">
        </div>
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-blue-500/5 rounded-full blur-3xl -mr-40 -mt-40"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-purple-500/5 rounded-full blur-3xl -ml-20 -mb-20">
        </div>
    </div>

    <div class="relative z-10 mx-auto max-w-4xl px-6 lg:px-8">
        <div class="text-center mb-16 animate-fade-in-up">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30 mb-6">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                <span class="text-xs font-bold uppercase tracking-widest">{{ __('words.help_center') }}</span>
            </div>

            <h1 class="text-4xl lg:text-5xl font-black text-zinc-900 dark:text-white tracking-tight mb-6">
                {{ __('words.faq') }}
            </h1>
            <p class="text-xl text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-2xl mx-auto font-medium">
                {{ __('words.faq_description') }}
            </p>
        </div>

        <div class="space-y-4 animate-fade-in-up delay-200">
            @forelse($faqs as $index => $faq)
                <div x-data="{ open: false }"
                    class="group bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 transition-all duration-300 hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-lg shadow-sm overflow-hidden"
                    :class="{ 'border-blue-500 dark:border-blue-500 ring-4 ring-blue-500/10': open }">
                    <button type="button"
                        class="flex w-full items-center justify-between p-6 md:p-8 text-left transition-colors"
                        @click="open = !open">
                        <span
                            class="text-lg font-bold text-zinc-900 dark:text-white pr-8 leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $faq->getTranslation('question', app()->getLocale()) }}
                        </span>
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-all shrink-0">
                            <svg class="w-5 h-5 transform transition-transform duration-300"
                                :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" x-collapse x-transition:enter="transition ease-out duration-200"
                        x-transition:leave="transition ease-in duration-150">
                        <div class="px-6 md:px-8 pb-8 pt-0">
                            <div class="h-px w-full bg-zinc-100 dark:bg-zinc-800 mb-6"></div>
                            <div
                                class="prose prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                {{ $faq->getTranslation('answer', app()->getLocale()) }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="text-center py-20 bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-dashed border-zinc-300 dark:border-zinc-700">
                    <div
                        class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4 text-zinc-400">
                        <x-app-icon name="question-mark-circle" class="w-8 h-8" />
                    </div>
                    <p class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('words.nothing_to_show') }}</p>
                    <p class="text-zinc-500 dark:text-zinc-400 mt-1">{{ __('words.check_back_later') }}</p>
                </div>
            @endforelse
        </div>
        {{-- show pagination links --}}
        <div class="mt-10 flex justify-center">
            {{ $faqs->onEachSide(1)->links() }}
        </div>
        
        {{-- Still have questions? --}}
        <div class="mt-20 text-center animate-fade-in-up delay-300">
            <p class="text-zinc-600 dark:text-zinc-400 mb-6 font-medium">{{ __('words.still_have_questions') }}</p>
            <a href="{{ route('contact') }}"
                class="inline-flex items-center gap-2 px-8 py-3 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-full font-bold hover:scale-105 transition-transform shadow-lg">
                {{ __('words.contact_support') }}
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
    <style>
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

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }
    </style>
</div>

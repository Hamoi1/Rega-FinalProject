<div class="relative min-h-screen bg-zinc-50 dark:bg-zinc-950 pt-32 pb-20 font-sans">

    {{-- Background Decoration --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div
            class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] dark:bg-[radial-gradient(#27272a_1px,transparent_1px)] [background-size:24px_24px] opacity-70">
        </div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16 animate-fade-in-up">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700 mb-6">
                <span class="w-2 h-2 rounded-full bg-zinc-500"></span>
                <span class="text-xs font-bold uppercase tracking-widest">{{ __('words.legal') }}</span>
            </div>

            <h1 class="text-4xl lg:text-5xl font-black text-zinc-900 dark:text-white tracking-tight mb-6">
                {{ __('words.privacy_policy') }}
            </h1>
            <p class="text-xl text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-2xl mx-auto font-medium">
                {{ __('words.privacy_intro') }}
            </p>
        </div>

        {{-- Content Card --}}
        <div
            class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 md:p-14 shadow-xl shadow-zinc-200/50 dark:shadow-black/20 border border-zinc-100 dark:border-zinc-800 animate-fade-in-up delay-200">
            <div class="prose prose-lg prose-zinc dark:prose-invert max-w-none">
                <p class="lead text-xl text-zinc-700 dark:text-zinc-300 font-medium">
                    {{ __('words.privacy_lead', ['company' => setting()->get('company_name', 'Rega')]) }}
                </p>

                <hr class="border-zinc-100 dark:border-zinc-800 my-12">

                <div class="space-y-12">
                    <section>
                        <h3 class="flex items-center gap-4 text-2xl font-bold text-zinc-900 dark:text-white mb-6">
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-lg font-black shadow-sm">1</span>
                            {{ __('words.privacy_collection_title') }}
                        </h3>
                        <div class="pl-14">
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                {{ __('words.privacy_collection_desc') }}
                            </p>
                        </div>
                    </section>

                    <section>
                        <h3 class="flex items-center gap-4 text-2xl font-bold text-zinc-900 dark:text-white mb-6">
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-lg font-black shadow-sm">2</span>
                            {{ __('words.privacy_usage_title') }}
                        </h3>
                        <div class="pl-14">
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                {{ __('words.privacy_usage_desc') }}
                            </p>
                        </div>
                    </section>

                    <section>
                        <h3 class="flex items-center gap-4 text-2xl font-bold text-zinc-900 dark:text-white mb-6">
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-lg font-black shadow-sm">3</span>
                            {{ __('words.privacy_sharing_title') }}
                        </h3>
                        <div class="pl-14">
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                {{ __('words.privacy_sharing_desc') }}
                            </p>
                        </div>
                    </section>

                    <section>
                        <h3 class="flex items-center gap-4 text-2xl font-bold text-zinc-900 dark:text-white mb-6">
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-lg font-black shadow-sm">4</span>
                            {{ __('words.privacy_retention_title') }}
                        </h3>
                        <div class="pl-14">
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                {{ __('words.privacy_retention_desc') }}
                            </p>
                        </div>
                    </section>

                    <section>
                        <h3 class="flex items-center gap-4 text-2xl font-bold text-zinc-900 dark:text-white mb-6">
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-lg font-black shadow-sm">5</span>
                            {{ __('words.privacy_changes_title') }}
                        </h3>
                        <div class="pl-14">
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                {{ __('words.privacy_changes_desc') }}
                            </p>
                        </div>
                    </section>
                </div>

                <hr class="border-zinc-100 dark:border-zinc-800 my-12">

                <div
                    class="flex items-center gap-3 text-sm font-bold text-zinc-500 dark:text-zinc-500 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-xl w-fit">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p>{{ __('words.last_updated') }}: {{ date('F j, Y') }}</p>
                </div>
            </div>
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
    </style>
</div>

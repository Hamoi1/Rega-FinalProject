<div id="main-loading"
    class="print:hidden fixed inset-0 z-[9999999] flex items-center justify-center bg-white/90 dark:bg-gray-900/90 backdrop-blur-md transition-all duration-300 ease-in-out">
    <div class="flex items-center justify-center h-full relative w-full">
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:30px_30px]">
        </div>
        <div class="flex flex-col items-center space-y-4">
            <div class="relative">
                <div
                    class="size-16 border-4 border-blue-200 dark:border-blue-800 rounded-full animate-spin border-t-blue-600 dark:border-t-blue-400">
                </div>
                <div
                    class="absolute inset-0 size-16 border-4 border-transparent rounded-full animate-ping border-t-blue-600 dark:border-t-blue-400">
                </div>
            </div>
            <p class="text-base xl:text-xl font-medium text-zinc-600 dark:text-zinc-400 animate-pulse">
                @lang('words.loading')
            </p>
        </div>
    </div>
</div>

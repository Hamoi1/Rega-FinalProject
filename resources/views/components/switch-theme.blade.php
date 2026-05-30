<button type="button" name="switch-theme" x-on:click="$store.theme.toggle()" x-on:keydown.ctrl.i.window="$el.click();"
    class="group inline-flex size-9 md:size-10 shrink-0 items-center justify-center rounded-xl border border-zinc-200 bg-white/90 text-zinc-600 shadow-sm transition hover:border-zinc-300 hover:bg-zinc-50 hover:text-zinc-950 active:scale-95 focus:outline-none focus:ring-2 focus:ring-zinc-500/15 dark:border-white/10 dark:bg-zinc-900/80 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-white">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="size-5 dark:hidden transition-all duration-200 group-hover:rotate-6 group-hover:scale-110">
        <path d="M12 3a6.364 6.364 0 0 0 9 9 9 9 0 1 1-9-9Z" />
    </svg>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="size-5 hidden dark:block transition-all duration-200 group-hover:rotate-45 group-hover:scale-110">
        <circle cx="12" cy="12" r="4" />
        <path d="m12 2 0 2" />
        <path d="m12 20 0 2" />
        <path d="m4.93 4.93 1.41 1.41" />
        <path d="m17.66 17.66 1.41 1.41" />
        <path d="m2 12 2 0" />
        <path d="m20 12 2 0" />
        <path d="m6.34 17.66-1.41 1.41" />
        <path d="m19.07 4.93-1.41 1.41" />
    </svg>
</button>

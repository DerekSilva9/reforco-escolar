<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-amber-50 dark:bg-slate-700 border border-blue-200 dark:border-slate-600 rounded-md font-semibold text-xs text-blue-950 dark:text-slate-200 uppercase tracking-widest shadow-sm hover:bg-amber-100 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 focus:ring-offset-amber-50 dark:focus:ring-offset-slate-900 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

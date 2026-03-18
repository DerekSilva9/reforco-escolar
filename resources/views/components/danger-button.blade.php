<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-rose-700 border border-rose-800 rounded-md font-semibold text-xs text-amber-50 uppercase tracking-widest hover:bg-rose-600 active:bg-rose-800 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:ring-offset-2 focus:ring-offset-amber-50 transition ease-in-out duration-150 shadow-sm']) }}>
    {{ $slot }}
</button>

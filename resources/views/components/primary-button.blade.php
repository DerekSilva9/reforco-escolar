@props(['disabled' => false])

<button @disabled($disabled) {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-blue-900 border border-blue-950 rounded-md font-semibold text-xs text-amber-50 uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:ring-offset-2 focus:ring-offset-amber-50 transition ease-in-out duration-150 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>

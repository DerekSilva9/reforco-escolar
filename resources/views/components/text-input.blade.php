@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-blue-200 dark:border-slate-600 focus:border-blue-700 dark:focus:border-blue-500 focus:ring-blue-700 dark:focus:ring-blue-500 rounded-md shadow-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500']) }}>

@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-blue-200 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm bg-white placeholder:text-slate-400']) }}>

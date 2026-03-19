@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-slate-600 dark:text-slate-100']) }}>
    {{ $value ?? $slot }}
</label>

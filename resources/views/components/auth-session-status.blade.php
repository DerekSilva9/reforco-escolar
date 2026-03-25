@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 px-4 py-3 rounded-xl shadow-sm text-sm font-medium']) }}>
        {{ $status }}
    </div>
@endif

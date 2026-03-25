<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            (function() {
                const theme = localStorage.getItem('theme') || 'light';
                const isDark = theme === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);

                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
    </head>
    <body class="font-sans text-slate-900 dark:text-slate-50 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 bg-gradient-to-br from-amber-50 via-amber-50 to-blue-50 dark:from-slate-950 dark:via-slate-900 dark:to-blue-950"
             style="font-family:'DM Sans',sans-serif;">
            <div class="w-full max-w-md px-6">
                <a href="/" class="flex items-center justify-center gap-3 group">
                    <x-application-logo class="w-12 h-12" />
                    <div class="text-left leading-tight">
                        <div style="font-family:'Cormorant Garamond',serif;" class="text-2xl font-bold text-blue-950 dark:text-amber-50 group-hover:text-blue-900 dark:group-hover:text-amber-100 transition-colors">
                            {{ config('app.name', 'Jardim do Saber') }}
                        </div>
                        <div class="text-xs text-slate-600 dark:text-slate-300 font-medium">
                            Acesso ao painel
                        </div>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white/90 dark:bg-slate-800/90 backdrop-blur shadow-md overflow-hidden sm:rounded-2xl border border-blue-100 dark:border-slate-700">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

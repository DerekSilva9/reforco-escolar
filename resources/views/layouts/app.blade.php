<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            // Applica o tema salvo no localStorage ou detecta preferência do sistema
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
    <body class="font-sans antialiased text-slate-900 dark:text-slate-50">
        <div class="min-h-screen bg-gradient-to-br from-amber-50 via-amber-50 to-blue-50 dark:from-slate-950 dark:via-slate-900 dark:to-blue-950">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 dark:bg-slate-800/80 backdrop-blur shadow-sm border-b border-blue-100 dark:border-slate-700">
                    <div class="max-w-7xl mx-auto py-4 md:py-6 lg:py-6 px-3 md:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @if (session('success'))
                    <div class="py-3 md:py-4">
                        <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8">
                            <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 px-3 md:px-4 py-2 md:py-3 rounded-md shadow-sm text-sm md:text-base">
                                {{ session('success') }}
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="py-3 md:py-4">
                        <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8">
                            <div class="bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200 px-3 md:px-4 py-2 md:py-3 rounded-md shadow-sm text-sm md:text-base">
                                {{ session('error') }}
                            </div>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>

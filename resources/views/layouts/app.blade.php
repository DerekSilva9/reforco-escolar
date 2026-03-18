<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div class="min-h-screen bg-gradient-to-br from-amber-50 via-amber-50 to-blue-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 backdrop-blur shadow-sm border-b border-blue-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @if (session('success'))
                    <div class="py-4">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-md shadow-sm">
                                {{ session('success') }}
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="py-4">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-md shadow-sm">
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

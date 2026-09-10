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
    <body class="font-sans text-nf-ink antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 islamic-pattern-light px-4">
            <div class="text-center">
                <a href="/" class="inline-block">
                    <x-application-logo class="w-20 h-20 rounded-3xl shadow-lg bg-white" />
                </a>
                <p class="mt-3 font-heading font-extrabold text-xl">Nurul Fikri <span class="text-nf-blue-dark">Balikpapan</span></p>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl border border-nf-blue/15 overflow-hidden rounded-3xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

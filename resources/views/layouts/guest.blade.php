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
        <link rel="icon" type="image/png" href="{{ asset('storage/kwas_hijau.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-fixed bg-cover bg-center h-screen overflow-hidden" style="background-image: url('{{ asset('storage/bg1.jpg') }}')">
        {{-- Navbar for guest pages --}}
        <nav class="w-full bg-green-700 text-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <img src="{{ asset('storage/kwas_putih_2.png') }}" alt="KWaS Logo" class="h-10 w-auto" />
                </div>
            </div>
        </nav>
            <div class="">
                {{ $slot }}
            </div>
    </body>

</html>

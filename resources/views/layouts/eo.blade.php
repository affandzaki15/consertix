<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Concertix - EO Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div id="app" class="min-h-screen">

        <!-- ================= NAVBAR EO ================= -->
        <header class="w-full bg-gradient-to-b from-[#0d0f55] to-[#0a0c38] text-white py-4 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">

                <!-- MOBILE -->
                <div class="md:hidden">

                    <div class="flex justify-center mb-4">
                        <a href="{{ route('eo.dashboard') }}">
                            <img src="{{ asset('logo/header.png') }}" class="h-10">
                        </a>
                    </div>

                    <div class="flex items-center justify-between text-sm font-medium">

                        <!-- Menu -->
                        <nav class="flex items-center space-x-4">
                            <a href="{{ route('eo.dashboard') }}" class="hover:text-indigo-300">Dashboard</a>
                        </nav>

                        <!-- Profile / Logout -->
                        @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="bg-white text-indigo-700 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ Auth::user()->name }}
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('eo.profile.edit')">
                                    Profil
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Logout
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        @endauth

                    </div>
                </div>

                <!-- DESKTOP -->
                <div class="hidden md:flex items-center justify-between">

                    <!-- Menu Kiri -->
                    <nav class="flex items-center space-x-8 text-sm lg:text-lg font-medium">
                        <a href="{{ route('eo.dashboard') }}" class="hover:text-indigo-300 transition">Dashboard</a>

                    </nav>

                    <!-- Logo Tengah -->
                    <a href="{{ route('eo.dashboard') }}" class="flex justify-center">
                        <img src="{{ asset('logo/header.png') }}" class="h-10 sm:h-12">
                    </a>

                    <!-- User Profile Kanan -->
                    <div class="flex items-center space-x-4">


                        @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center space-x-2 bg-white text-indigo-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-100">
                                    <span>{{ Auth::user()->name }}</span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                  <x-dropdown-link :href="route('eo.profile.edit')">
                                    Profil
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Logout
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        @endauth
                    </div>
                </div>

            </div>
        </header>


        <!-- MAIN CONTENT -->
        <main class="pt-6 pb-10 px-4">
            @yield('content')
        </main>

    </div>
</body>

</html>
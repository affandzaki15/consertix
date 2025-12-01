<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
     <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div id="app" class="{{ request()->is('admin*') ? 'min-h-screen' : '' }}">

        {{-- Header: hide on authentication pages (login/register/password) --}}
        @php
            $isAuthPage = request()->routeIs('login') || request()->routeIs('register') || request()->is('password/*');
        @endphp
        {{-- Header --}}
        @if (request()->is('admin*'))
            {{-- Admin Header + Menu --}}
            @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')
        @elseif(!$isAuthPage)
            {{-- User Header --}}
            <header class="w-full bg-gradient-to-b from-[#0d0f55] to-[#0a0c38] text-white py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6">

                    <!-- Mobile -->
                    <div class="md:hidden">
                        <div class="flex justify-center mb-4">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('logo/header.png') }}" class="h-10">
                            </a>
                        </div>
                        <div class="flex items-center justify-between">
                            <nav class="flex items-center space-x-4 text-sm font-medium">
                                <a href="{{ url('/') }}" class="hover:text-indigo-300">Home</a>
                                <a href="{{ route('concerts.index') }}" class="hover:text-indigo-300">Concerts</a>
                                <a href="{{ route('about') }}" class="hover:text-indigo-300">About</a>
                            </nav>

                        <!-- LOGIN + CART -->
                        <div class="flex items-center space-x-3">

                            <!-- Cart -->
                            <a href="{{ route('cart.show') }}" class="text-white hover:text-indigo-300 relative">
                                <i class="fa-solid fa-cart-shopping text-lg"></i>
                                @php
                                    $cart = session()->get('cart', []);
                                    $count = 0;
                                    foreach($cart as $items) {
                                        foreach($items as $qty) {
                                            $count += (int) $qty;
                                        }
                                    }
                                @endphp
                                @if($count > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $count }}</span>
                                @endif
                            </a>

                            @guest
                            <a href="{{ route('login') }}"
                                class="bg-white text-indigo-700 px-3 py-1 rounded-full text-xs font-medium">
                                Login / Register
                            </a>
                            @else
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="flex items-center space-x-1 bg-white text-indigo-700 px-3 py-1 rounded-full text-xs font-medium hover:bg-gray-100">
                                        <span>{{ Auth::user()->name }}</span>
                                        <i class="fa-solid fa-chevron-down text-xs text-indigo-700"></i>
                                    </button>
                                </x-slot>

                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('history')">
                                                <i class="fa-solid fa-clock-rotate-left mr-2"></i>History
                                            </x-dropdown-link>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <x-dropdown-link href="{{ route('logout') }}"
                                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                                    <i class="fa-solid fa-sign-out-alt mr-2"></i>Logout
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                @endguest
                            </div>
                        </div>
                    </div>

                    <!-- Desktop -->
                    <div class="hidden md:flex flex-wrap items-center justify-between gap-4">
                        <nav class="flex items-center space-x-8 text-sm lg:text-lg font-medium order-1">
                            <a href="{{ url('/') }}" class="hover:text-indigo-300 transition">Home</a>
                            <a href="{{ route('concerts.index') }}" class="hover:text-indigo-300 transition">Concerts</a>
                            <a href="{{ route('about') }}" class="hover:text-indigo-300 transition">About</a>
                        </nav>

                        <div class="flex-1 flex justify-center order-2">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('logo/header.png') }}" class="h-10 sm:h-12">
                            </a>
                        </div>

                    <!-- LOGIN + CART RIGHT -->
                    <div class="flex items-center space-x-4 order-3">

                        <!-- Cart -->
                        <a href="{{ route('cart.show') }}" class="text-white hover:text-indigo-300 transition relative">
                            <i class="fa-solid fa-cart-shopping text-xl md:text-2xl"></i>
                            @php
                                $cart = session()->get('cart', []);
                                $count = 0;
                                foreach($cart as $items) {
                                    foreach($items as $qty) {
                                        $count += (int) $qty;
                                    }
                                }
                            @endphp
                            @if($count > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $count }}</span>
                            @endif
                        </a>

                            @guest
                                <a href="{{ route('login') }}"
                                    class="bg-white text-indigo-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-100 transition">
                                    Login / Register
                                </a>
                            @else
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="flex items-center space-x-2 bg-white text-indigo-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-100">
                                            <span>{{ Auth::user()->name }}</span>
                                            <i class="fa-solid fa-chevron-down text-sm text-indigo-700"></i>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('history')">
                                            <i class="fa-solid fa-clock-rotate-left mr-2"></i>History
                                        </x-dropdown-link>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                <i class="fa-solid fa-sign-out-alt mr-2"></i>Logout
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endguest
                        </div>
                    </div>

                </div>
            </header>
        @endif

        

        <!-- Page Content -->
        <main class="{{ request()->is('admin*') ? 'pt-[80px] md:pt-[96px]' : 'pt-0' }}">
            @yield('content')
        </main>
        <!-- Payment icons strip (moved; rendered below footer) -->
    </div>

    
    <!-- Font Awesome (load once for all pages) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

    @stack('scripts')
</body>

</html>

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
                            <button type="button" id="openCartBtn" class="text-white hover:text-indigo-300 relative cursor-pointer">
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
                            </button>

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
                        <button type="button" id="openCartBtnDesktop" class="text-white hover:text-indigo-300 transition relative cursor-pointer">
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
                        </button>

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

    <!-- Cart Modal -->
    <div id="cartModal" class="fixed inset-0 z-50 hidden items-end md:items-center justify-center" data-cart-backdrop>
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white w-full md:max-w-2xl max-h-[80vh] overflow-y-auto rounded-t-2xl md:rounded-2xl shadow-xl">
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Shopping Cart</h2>
                <button type="button" id="closeCartBtn" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Cart Items -->
            <div id="cartItemsContainer" class="p-6">
                <!-- Items will be loaded here via JavaScript -->
            </div>

            <!-- Footer with Action Buttons -->
            <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 space-y-3">
                <a href="{{ route('cart.show') }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-xl font-semibold transition">
                    View Full Cart
                </a>
                <button type="button" id="closeCartBtnFooter" class="w-full text-gray-700 hover:text-gray-900 px-4 py-3 font-semibold transition">
                    Continue Shopping
                </button>
            </div>
        </div>
    </div>

    <script>
        // Cart Modal Management
        (function() {
            const cartModal = document.getElementById('cartModal');
            const openCartBtn = document.getElementById('openCartBtn');
            const openCartBtnDesktop = document.getElementById('openCartBtnDesktop');
            const closeCartBtn = document.getElementById('closeCartBtn');
            const closeCartBtnFooter = document.getElementById('closeCartBtnFooter');
            const backdrop = cartModal.querySelector('[data-cart-backdrop]');
            const cartItemsContainer = document.getElementById('cartItemsContainer');

            function showCartModal() {
                cartModal.classList.remove('hidden');
                cartModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                loadCartItems();
            }

            function hideCartModal() {
                cartModal.classList.add('hidden');
                cartModal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            function loadCartItems() {
                fetch('{{ route('cart.show') }}')
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const cartContent = doc.querySelector('.space-y-4');
                        
                        if (cartContent) {
                            cartItemsContainer.innerHTML = cartContent.innerHTML;
                        } else {
                            cartItemsContainer.innerHTML = '<p class="text-center text-gray-600 py-8">Your cart is empty</p>';
                        }
                    })
                    .catch(err => console.error('Error loading cart:', err));
            }

            if (openCartBtn) {
                @guest
                    openCartBtn.addEventListener('click', function() {
                        window.location.href = "{{ route('login') }}";
                    });
                @else
                    openCartBtn.addEventListener('click', showCartModal);
                @endguest
            }
            
            if (openCartBtnDesktop) {
                @guest
                    openCartBtnDesktop.addEventListener('click', function() {
                        window.location.href = "{{ route('login') }}";
                    });
                @else
                    openCartBtnDesktop.addEventListener('click', showCartModal);
                @endguest
            }

            if (closeCartBtn) {
                closeCartBtn.addEventListener('click', hideCartModal);
            }

            if (closeCartBtnFooter) {
                closeCartBtnFooter.addEventListener('click', hideCartModal);
            }

            if (backdrop) {
                backdrop.addEventListener('click', hideCartModal);
            }

            // Close on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !cartModal.classList.contains('hidden')) {
                    hideCartModal();
                }
            });

            // Cart badge update function (called from other pages)
            window.updateCartBadge = function(count) {
                // Find all cart buttons
                const cartButtons = [
                    document.getElementById('openCartBtn'),
                    document.getElementById('openCartBtnDesktop')
                ];

                cartButtons.forEach(btn => {
                    if (!btn) return;

                    // Find or create badge span
                    let badge = btn.querySelector('span');
                    
                    if (count > 0) {
                        if (!badge) {
                            // Create badge if it doesn't exist
                            badge = document.createElement('span');
                            badge.className = 'absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center';
                            btn.appendChild(badge);
                        }
                        badge.textContent = count;
                    } else {
                        // Remove badge if count is 0
                        if (badge) {
                            badge.remove();
                        }
                    }
                });

                // Reload cart items in modal if it's open
                if (!cartModal.classList.contains('hidden')) {
                    loadCartItems();
                }
            };
        })();
    </script>

    @stack('scripts')
</body>

</html>

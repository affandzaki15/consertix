{{-- Sticky Admin Header --}}
@php
    $menuItems = [
        ['label' => 'Dashboard',   'routes' => ['admin.dashboard']],
        ['label' => 'Users',       'routes' => ['admin.users.index', 'admin.users']],
        ['label' => 'Organizers',  'routes' => ['admin.organizers.pending', 'admin.organizers.index']],
        ['label' => 'Concerts',    'routes' => ['admin.concerts.pending', 'admin.concerts.index']],
        ['label' => 'Orders',      'routes' => ['admin.orders.index']],
        ['label' => 'Reports',     'routes' => ['admin.reports.index']],
    ];
@endphp

<header class="w-full bg-gradient-to-b from-[#0d0f55] to-[#0a0c38] text-white py-4 shadow-lg fixed top-0 left-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <!-- MOBILE -->
        <div class="md:hidden">
            <div class="flex justify-center mb-4">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('logo/header.png') }}" class="h-10" alt="Logo">
                </a>
            </div>

            <div class="flex items-center justify-between text-sm font-medium">
                <nav class="flex items-center space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-300">Dashboard</a>
                    <a href="{{ route('admin.concerts.index') }}" class="hover:text-indigo-300">Konser</a>
                </nav>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="bg-white text-indigo-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ Auth::user()->name }}
                            </button>
                        </x-slot>
                        <x-slot name="content">
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
            <nav class="flex items-center space-x-6 text-sm lg:text-base font-medium">
                @foreach($menuItems as $item)
                    @php
                        $active = false;
                        $url = '#';
                        foreach ($item['routes'] as $route) {
                            if (Route::has($route)) {
                                $url = route($route);
                                if (request()->routeIs($route . '*') || request()->route()?->getName() === $route) {
                                    $active = true;
                                }
                                break;
                            }
                        }
                    @endphp
                    @if($url !== '#')
                        <a href="{{ $url }}"
                           class="{{ $active ? 'text-white font-semibold' : 'text-gray-200 hover:text-indigo-300' }} transition">
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <a href="{{ route('admin.dashboard') }}" class="flex justify-center">
                <img src="{{ asset('logo/header.png') }}" class="h-10 sm:h-12" alt="Admin Logo">
            </a>

            <div class="flex items-center space-x-4">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 bg-white text-indigo-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-100">
                                <span>{{ Auth::user()->name }}</span>
                            </button>
                        </x-slot>
                        <x-slot name="content">
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

{{-- ✅ Spacer yang diperbaiki — responsif dan pas! --}}
<div class="h-[100px] md:h-[112px] lg:h-[120px]"></div>
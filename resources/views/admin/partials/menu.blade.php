{{-- Sticky Admin Menu --}}
@php
    $menuItems = [
        ['label' => 'Dashboard',   'routes' => ['admin.dashboard']],
        ['label' => 'Users',       'routes' => ['admin.users.index', 'admin.users']],
        ['label' => 'Organizers',  'routes' => ['admin.organizers.pending', 'admin.organizers.index']],
        ['label' => 'Concerts',    'routes' => ['admin.concerts.pending', 'admin.concerts.index']],
        ['label' => 'Orders',      'routes' => ['admin.orders.index']],
        ['label' => 'Payments',    'routes' => ['admin.payments.index']],
        ['label' => 'Tickets',     'routes' => ['admin.tickets.index']],
        ['label' => 'Reports',     'routes' => ['admin.reports.index']],
    ];
@endphp

<div class="fixed top-0 left-0 w-full z-40 bg-[#0d0f55] text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between py-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo/header-white.png') }}" class="h-8">
                <span class="text-lg font-semibold">Admin Panel</span>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <span class="text-sm">{{ Auth::user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm font-medium">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex items-center overflow-x-auto py-3 -mx-2 border-t border-indigo-800">
            @foreach($menuItems as $item)
                @php
                    $resolvedName = null;
                    foreach ($item['routes'] as $candidate) {
                        if (\Illuminate\Support\Facades\Route::has($candidate)) {
                            $resolvedName = $candidate;
                            break;
                        }
                    }

                    if ($resolvedName) {
                        $url = route($resolvedName);
                        $active = request()->routeIs($resolvedName . '*')
                            || in_array(request()->route()?->getName(), $item['routes']);
                    } else {
                        $url = '#';
                        $active = false;
                    }

                    $baseClasses = 'inline-flex items-center gap-2 px-4 py-2 mx-2 text-sm font-medium rounded-md whitespace-nowrap transition-all duration-150';
                    $activeClasses = $active
                        ? 'bg-indigo-500 text-white shadow'
                        : 'text-gray-300 hover:bg-indigo-700 hover:text-white';
                @endphp

                @if($resolvedName)
                    <a href="{{ $url }}" 
                       class="{{ $baseClasses }} {{ $activeClasses }}"
                       aria-current="{{ $active ? 'page' : 'false' }}">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span 
                       class="{{ $baseClasses }} text-gray-500 cursor-not-allowed">
                        {{ $item['label'] }}
                    </span>
                @endif
            @endforeach
        </nav>

    </div>
</div>



<div class="bg-white shadow-sm rounded-md p-3 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            {{-- Back button untuk halaman show/edit --}}
            @php $prev = url()->previous(); @endphp
            @if($prev && !str_contains($prev, url()->current()))
                <a href="{{ $prev }}" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-50 text-gray-700 hover:bg-gray-100">
                    &larr; Kembali
                </a>
            @endif

            {{-- Shortcut links (cek ketersediaan route sebelum render) --}}
            @if(Route::has('admin.dashboard'))
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">Dashboard</a>
            @endif

            @if(Route::has('admin.users.index'))
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">Users</a>
            @endif

            @if(Route::has('admin.organizers.pending'))
                <a href="{{ route('admin.organizers.pending') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.organizers.*') ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">Organizers</a>
            @endif

            @if(Route::has('admin.concerts.pending'))
                <a href="{{ route('admin.concerts.pending') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.concerts.*') ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">Concerts</a>
            @endif

            @if(Route::has('admin.orders.index'))
                <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">Orders</a>
            @endif

            @if(Route::has('admin.payments.index'))
                <a href="{{ route('admin.payments.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">Payments</a>
            @endif

            @if(Route::has('admin.tickets.index'))
                <a href="{{ route('admin.tickets.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.tickets.*') ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">Tickets</a>
            @endif

            @if(Route::has('admin.reports.index'))
                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">Reports</a>
            @endif
        </div>

        {{-- Optional: quick actions --}}
        <div class="flex items-center gap-2">
            @if(Route::has('admin.users.index'))
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-md bg-indigo-600 text-white text-sm">Manage Users</a>
            @endif
        </div>
    </div>
</div>
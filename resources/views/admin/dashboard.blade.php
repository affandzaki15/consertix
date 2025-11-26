@extends('layouts.app')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Admin Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600">Ringkasan cepat: pengguna, EO, penjualan, dan order terbaru.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-md text-sm shadow-sm hover:shadow focus:outline-none">
                    Export Reports
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                    Manage Users
                </a>
            </div>
        </div>

        <!-- Admin Menu (compact) -->
        <nav class="mb-6">
            <div class="flex flex-wrap gap-2">
                @include('admin.partials.menu')
            </div>
        </nav>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm text-gray-500">Total Users</h3>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $usersCount ?? 0 }}</p>
                    </div>
                    <div class="text-indigo-600 bg-indigo-50 p-2 rounded-full">
                        <!-- icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5.121 17.804A9 9 0 1118.88 6.195" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-500">Jumlah user terdaftar</p>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm text-gray-500">Organizers (EO)</h3>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $eoCount ?? 0 }}</p>
                    </div>
                    <div class="text-green-600 bg-green-50 p-2 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 11c2.21 0 4-1.79 4-4S14.21 3 12 3 8 4.79 8 7s1.79 4 4 4z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-500">Jumlah EO aktif</p>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm text-gray-500">Pending Payments</h3>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $pendingPayments ?? 0 }}</p>
                    </div>
                    <div class="text-purple-600 bg-purple-50 p-2 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-500">Order dengan bukti belum terverifikasi</p>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm text-gray-500">Sales Total</h3>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">Rp {{ number_format($salesTotal ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-yellow-600 bg-yellow-50 p-2 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c4 0 8 2 8 6s-4 6-8 6-8-2-8-6 4-6 8-6z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-500">Total pendapatan</p>
            </div>
        </div>

        <!-- Main content grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Concerts by status -->
            <div class="lg:col-span-1 bg-white p-5 rounded-lg shadow-sm border">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Concerts by Status</h3>
                <ul class="space-y-2 text-sm">
                    @forelse($concertsByStatus as $status => $count)
                        <li class="flex items-center justify-between">
                            <span class="capitalize text-gray-700">{{ $status }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500">Tidak ada data konser.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Recent orders -->
            <div class="lg:col-span-2 bg-white p-5 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
                        <input name="q" type="search" placeholder="Cari order / user" class="border rounded-md px-3 py-2 text-sm" />
                        <button class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">View all</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs text-gray-500">#</th>
                                <th class="px-4 py-3 text-left text-xs text-gray-500">User</th>
                                <th class="px-4 py-3 text-left text-xs text-gray-500">Total</th>
                                <th class="px-4 py-3 text-left text-xs text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $order->id }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $usersMap[$order->user_id]->name ?? '-' }}</td>
                                    <td class="px-4 py-3">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ ($order->payment_status ?? $order->status ?? '') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $order->payment_status ?? $order->status ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-3" colspan="4">Belum ada order.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $recentOrders->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
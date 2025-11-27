hi
@extends('layouts.app')
@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Admin Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Ringkasan cepat: pengguna, EO, penjualan, dan order terbaru.
                </p>
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
                <h3 class="text-sm text-gray-500">Total Users</h3>
                <p class="mt-1 text-2xl font-semibold">{{ $usersCount ?? 0 }}</p>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <h3 class="text-sm text-gray-500">Organizers (EO)</h3>
                <p class="mt-1 text-2xl font-semibold">{{ $eoCount ?? 0 }}</p>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <h3 class="text-sm text-gray-500">Pending Payments</h3>
                <p class="mt-1 text-2xl font-semibold">{{ $pendingPayments ?? 0 }}</p>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <h3 class="text-sm text-gray-500">Sales Total</h3>
                <p class="mt-1 text-2xl font-semibold">
                    Rp {{ number_format($salesTotal ?? 0, 0, ',', '.') }}
                </p>
            </div>

        </div>

        <!-- Main content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Approval Status -->
            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <h3 class="text-lg font-semibold mb-3">Concert Approval Status</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between">
                        <span>Pending</span>
                        <span>{{ $approvalStats['pending'] ?? 0 }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Approved</span>
                        <span>{{ $approvalStats['approved'] ?? 0 }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Rejected</span>
                        <span>{{ $approvalStats['rejected'] ?? 0 }}</span>
                    </li>
                </ul>
            </div>

            <!-- Selling Status -->
            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <h3 class="text-lg font-semibold mb-3">Selling Status (Approved)</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between">
                        <span>Coming Soon</span>
                        <span>{{ $sellingStats['coming_soon'] ?? 0 }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Tiket Tersedia</span>
                        <span>{{ $sellingStats['available'] ?? 0 }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Sold Out</span>
                        <span>{{ $sellingStats['sold_out'] ?? 0 }}</span>
                    </li>
                </ul>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white p-5 rounded-lg shadow-sm border lg:col-span-1">
                <h3 class="text-lg font-semibold mb-3">Recent Orders</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">Total</th>
                                <th class="px-3 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="px-3 py-2">{{ $order->id }}</td>
                                    <td class="px-3 py-2">
                                        Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-2">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            {{ ($order->payment_status ?? 'pending') === 'pending'
                                                ? 'bg-yellow-200 text-yellow-800'
                                                : 'bg-green-200 text-green-800' }}">
                                            {{ $order->payment_status ?? 'pending' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-3 text-center text-gray-500">
                                        Tidak ada order terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $recentOrders->links() }}</div>
            </div>

        </div>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
    <style>
        .chart-canvas {
            height: 200px !important;
            width: 100% !important;
        }

        /* Custom hover for cards */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        /* Subtle animation for badges */
        .badge-animate {
            transition: all 0.2s ease-in-out;
        }
        .badge-animate:hover {
            transform: scale(1.05);
        }

        /* Smooth scroll for table */
        .table-scroll {
            max-height: 300px;
            overflow-y: auto;
        }

        /* Fade-in Up Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        /* Staggered animation for KPI cards */
        .grid-fade .bg-white { opacity: 0; }
        .grid-fade.loaded .bg-white {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .grid-fade.loaded .bg-white:nth-child(1) { animation-delay: 0.1s; }
        .grid-fade.loaded .bg-white:nth-child(2) { animation-delay: 0.2s; }
        .grid-fade.loaded .bg-white:nth-child(3) { animation-delay: 0.3s; }
        .grid-fade.loaded .bg-white:nth-child(4) { animation-delay: 0.4s; }

        /* Chart container animation */
        .chart-container {
            opacity: 0;
            animation: fadeInUp 0.7s ease-out 0.5s forwards;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8 mt-8">
            <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
            <p class="text-gray-600 mt-1">
                Ringkasan performa: pengguna, organizer, penjualan, dan aktivitas terbaru.
            </p>
        </div>

        <!-- Admin Menu -->
        @if(View::exists('admin.partials.menu'))
            <div class="mb-8">
                @include('admin.partials.menu')
            </div>
        @endif

        <!-- KPI Cards - Grid 4 kolom -->
        <div id="kpi-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 grid-fade">
            <!-- Total Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-50 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Pengguna</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($usersCount ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Organizers (EO) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-50 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Organizer (EO)</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($eoCount ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-50 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pembayaran Tertunda</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingPayments ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Sales Total -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-50 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Penjualan</p>
                        <p class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($salesTotal ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik & Order Terbaru -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Approval Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.5s;">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Persetujuan Konser</h3>
                <ul class="space-y-3">
                    <li class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-gray-600">Menunggu</span>
                        <span class="badge-animate px-2.5 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                            {{ $approvalStats['pending'] ?? 0 }}
                        </span>
                    </li>
                    <li class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-gray-600">Disetujui</span>
                        <span class="badge-animate px-2.5 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            {{ $approvalStats['approved'] ?? 0 }}
                        </span>
                    </li>
                    <li class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-gray-600">Ditolak</span>
                        <span class="badge-animate px-2.5 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                            {{ $approvalStats['rejected'] ?? 0 }}
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Selling Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.6s;">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Penjualan (Disetujui)</h3>
                <ul class="space-y-3">
                    <li class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-gray-600">Coming Soon</span>
                        <span class="badge-animate px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            {{ $sellingStats['coming_soon'] ?? 0 }}
                        </span>
                    </li>
                    <li class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-gray-600">Tiket Tersedia</span>
                        <span class="badge-animate px-2.5 py-0.5 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                            {{ $sellingStats['available'] ?? 0 }}
                        </span>
                    </li>
                    <li class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-gray-600">Sold Out</span>
                        <span class="badge-animate px-2.5 py-0.5 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                            {{ $sellingStats['sold_out'] ?? 0 }}
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.7s;">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Order Terbaru</h3>
                    @if($recentOrders->isNotEmpty())
                        <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            Lihat Semua
                        </a>
                    @endif
                </div>

                @if($recentOrders->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Tidak ada order terbaru.
                    </div>
                @else
                    <div class="table-scroll">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-3 py-2">ID</th>
                                    <th class="px-3 py-2">Total</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-3 py-3 font-medium text-gray-900">{{ $order->id }}</td>
                                        <td class="px-3 py-3 text-gray-700">
                                            Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3">
                                            @php
                                                $status = $order->status ?? 'pending';
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($status === 'paid') bg-green-100 text-green-800
                                                @elseif($status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $status === 'paid' ? 'Lunas' : ($status === 'pending' ? 'Tertunda' : 'Lainnya') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="mt-10 chart-container">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Laporan Visual</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Chart: Konser per Bulan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Konser Disetujui vs Ditolak (6 Bulan Terakhir)</h3>
                    <canvas id="concertChart" class="chart-canvas"></canvas>
                </div>

                <!-- Chart: Penjualan per Bulan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Penjualan Bulanan (Terkonfirmasi)</h3>
                    <canvas id="salesChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:justify-start space-x-6">
                    <p class="text-sm text-gray-600">
                        &copy; {{ date('Y') }} {{ config('app.name', 'EventApp') }}. All rights reserved.
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex justify-center space-x-6">
                    <a href="#" class="text-gray-500 hover:text-gray-700 text-sm transition-colors">
                        Kebijakan Privasi
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 text-sm transition-colors">
                        Syarat & Ketentuan
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 text-sm transition-colors">
                        Bantuan
                    </a>
                </div>
            </div>
            <div class="mt-6 text-center md:text-left">
                <p class="text-xs text-gray-500">
                    Sistem Manajemen Event & Tiket — Dikembangkan dengan ❤️ untuk Organizer Indonesia
                </p>
            </div>
        </div>
    </footer>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Trigger KPI card animation
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                const grid = document.getElementById('kpi-grid');
                if (grid) grid.classList.add('loaded');
            }, 100);
        });

        // Chart: Konser
        const concertCtx = document.getElementById('concertChart').getContext('2d');
        new Chart(concertCtx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: 'Disetujui',
                        data: @json($approvedData),
                        backgroundColor: '#3b82f6',
                        borderRadius: 4,
                        borderSkipped: false,
                        borderWidth: 1,
                        borderColor: '#2563eb'
                    },
                    {
                        label: 'Ditolak',
                        data: @json($rejectedData),
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                        borderSkipped: false,
                        borderWidth: 1,
                        borderColor: '#dc2626'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                family: 'Figtree'
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280', font: { family: 'Figtree' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            color: '#6b7280',
                            font: { family: 'Figtree' },
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });

        // Chart: Penjualan
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: @json($salesMonths),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: @json($salesData),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280', font: { family: 'Figtree' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            color: '#6b7280',
                            font: { family: 'Figtree' },
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
@endsection
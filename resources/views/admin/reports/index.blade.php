@extends('layouts.app')

@section('content')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .report-card {
            opacity: 0;
            animation: fadeIn 0.6s ease-out forwards;
        }
        .report-card:nth-child(1) { animation-delay: 0.1s; }
        .report-card:nth-child(2) { animation-delay: 0.2s; }
        .report-card:nth-child(3) { animation-delay: 0.3s; }
        .report-card:nth-child(4) { animation-delay: 0.4s; }

        .chart-container {
            height: 250px;
        }
    </style>

    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Laporan & Analitik</h1>
                <p class="text-gray-600 mt-1">Ringkasan performa platform Anda dalam periode terpilih.</p>
            </div>

          <!-- Filter Tanggal -->
<div class="bg-white rounded-xl shadow-sm p-5 mb-8 border border-gray-200">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Dari</label>
            <input 
                type="date" 
                name="start_date" 
                value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                required>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Sampai</label>
            <input 
                type="date" 
                name="end_date" 
                value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                required>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                Terapkan Filter
            </button>
            <a href="{{ route('admin.reports.index') }}"
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg shadow-sm transition">
                Reset
            </a>
        </div>
    </form>
</div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Total Users -->
                <div class="report-card bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($usersCount) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Organizers -->
                <div class="report-card bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M12 12h.01" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Organizer</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($organizersCount) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Concerts -->
                <div class="report-card bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-amber-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Konser</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($concertsCount) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Sales -->
                <div class="report-card bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Penjualan</p>
                            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Penjualan Harian ({{ $startDate->format('d M Y') }} – {{ $endDate->format('d M Y') }})</h3>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'EventFlow') }} — Laporan Analitik
        </div>
    </footer>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: @json($salesData),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
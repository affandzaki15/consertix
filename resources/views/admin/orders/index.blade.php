@extends('layouts.app')

@section('content')
    <style>
        /* Animasi baris tabel (hanya saat load awal) */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-on-load {
            opacity: 0;
            animation: fadeInUp 0.5s cubic-bezier(0.22, 0.61, 0.36, 1) forwards;
        }

        /* Staggered delay */
        @for ($i = 1; $i <= 30; $i++)
            .animate-on-load:nth-child({{ $i }}) { animation-delay: {{ 0.1 + ($i - 1) * 0.04 }}s; }
        @endfor

        /* Hover */
        .data-row:hover {
            background-color: #f8fafc !important;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
        }
    </style>

    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900">Orders</h2>
            <div class="overflow-x-auto mt-4 shadow rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No.</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Concert</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Buyer</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Payment</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($orders as $order)
                            <tr class="data-row animate-on-load hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-4 text-center text-sm font-medium text-slate-700">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4 text-sm font-mono text-slate-600">{{ $order->id }}</td>
                                <td class="px-5 py-4">
                                    @if($order->concert)
                                        <span class="font-semibold text-indigo-700">{{ $order->concert->title ?? $order->concert->name }}</span>
                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ \Carbon\Carbon::parse($order->concert->date)->translatedFormat('d F Y') }}
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">Concert deleted</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm font-medium text-slate-800">{{ strtoupper($order->buyer_name ?? '-') }}</td>
                                <td class="px-5 py-4 text-sm text-slate-700">{{ $order->buyer_email ?? '-' }}</td>
                                <td class="px-5 py-4 text-sm font-medium text-slate-800">{{ strtoupper($order->payment_method ?? '-') }}</td>
                                <td class="px-5 py-4 text-sm font-bold text-slate-900">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">
                                    @php
                                        $status = $order->status ?? 'unknown';
                                        $badgeClass = match($status) {
                                            'completed' => 'bg-teal-100 text-teal-800',
                                            'processing' => 'bg-amber-100 text-amber-800',
                                            'paid' => 'bg-indigo-100 text-indigo-800',
                                            'failed' => 'bg-rose-100 text-rose-800',
                                            default => 'bg-slate-100 text-slate-800'
                                        };
                                        $displayStatus = match($status) {
                                            'completed' => 'SELESAI',
                                            'processing' => 'SEDANG DIPROSES',
                                            'paid' => 'SUDAH BAYAR',
                                            'failed' => 'GAGAL',
                                            default => 'UNKNOWN'
                                        };
                                        $icon = match($status) {
                                            'completed' => 'M5 13l4 4L19 7',
                                            'processing' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'paid' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                                            'failed' => 'M6 18L18 6M6 6l12 12',
                                            default => 'M4 6h16M4 12h16M4 18h16'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                                        </svg>
                                        {{ $displayStatus }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @include('admin.orders.row-actions', ['order' => $order])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-12 text-center text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Tidak ada order ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pesan saat hasil pencarian kosong -->
                <div id="noResultsMessage" class="hidden px-5 py-8 text-center text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Tidak ada order yang cocok dengan pencarian Anda.
                                </div>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="md:flex md:items-center md:justify-between">
                <div class="text-sm text-slate-600">
                    &copy; {{ date('Y') }} {{ config('app.name', 'EventFlow') }}. All rights reserved.
                </div>
                <div class="mt-3 md:mt-0 flex flex-wrap justify-center gap-x-5 gap-y-2">
                    <a href="#" class="text-slate-500 hover:text-slate-700 text-sm transition-colors">Privacy Policy</a>
                    <a href="#" class="text-slate-500 hover:text-slate-700 text-sm transition-colors">Terms of Service</a>
                    <a href="#" class="text-slate-500 hover:text-slate-700 text-sm transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ✨ JavaScript Pencarian Real-Time -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchOrders');
            const table = document.getElementById('ordersTable');
            const rows = table.querySelectorAll('.data-row');
            const noResults = document.getElementById('noResultsMessage');

            // Hilangkan animasi setelah load agar filter tidak ganggu
            setTimeout(() => {
                rows.forEach(row => row.classList.remove('animate-on-load'));
            }, 1000);

            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(query)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Tampilkan/sembunyikan pesan "tidak ada hasil"
                if (visibleCount === 0 && query.length > 0) {
                    noResults.classList.remove('hidden');
                    table.classList.add('!border-transparent');
                } else {
                    noResults.classList.add('hidden');
                    table.classList.remove('!border-transparent');
                }
            });
        });
    </script>
@endsection
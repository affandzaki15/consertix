@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-2">Riwayat Pesanan Tiket</h1>
        <p class="text-gray-600 mb-6">Daftar semua tiket yang telah Anda beli dan bayar</p>

        @if ($orders->count() > 0)
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                            <div class="flex-1 mb-3 md:mb-0">
                                <!-- Concert Info -->
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $order->concert->name ?? 'Concert' }}
                                </h3>
                                
                                <!-- Order Details -->
                                <div class="grid grid-cols-2 gap-3 text-sm text-gray-600 mt-2">
                                    <div>
                                        <span class="font-medium">Reference Code:</span>
                                        <p class="font-mono text-blue-600">{{ $order->reference_code }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium">Tanggal Concert:</span>
                                        <p>{{ \Carbon\Carbon::parse($order->concert->date)->format('d M Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium">Total Tiket:</span>
                                        <p>{{ $order->items->sum('quantity') }} tiket</p>
                                    </div>
                                    <div>
                                        <span class="font-medium">Total Harga:</span>
                                        <p class="text-green-600 font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="mt-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        ✓ Sudah Dibayar
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="flex gap-2">
                                <a href="{{ route('history.show', $order->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat Tiket
                                </a>
                            </div>
                        </div>

                        <!-- Ticket Items Preview -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm font-medium text-gray-700 mb-2">Detail Tiket:</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                @foreach ($order->items as $item)
                                    <div class="text-sm bg-gray-50 p-2 rounded">
                                        <span class="font-medium">{{ $item->ticketType->name }}</span>
                                        <span class="text-gray-600">x{{ $item->quantity }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Belum ada pesanan</h3>
                <p class="mt-1 text-gray-500">Anda belum melakukan pembelian tiket apapun.</p>
                <div class="mt-6">
                    <a href="{{ route('concerts.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Jelajahi Konser
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

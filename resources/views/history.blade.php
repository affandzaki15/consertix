@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-2">Riwayat Pesanan Tiket</h1>
        <p class="text-gray-600 mb-6">Daftar semua tiket yang telah Anda beli dan bayar</p>

        @if ($orders->count() > 0)
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="relative bg-white border border-gray-200 rounded-xl overflow-hidden p-3">
                        <!-- smaller ticket notches -->
                        <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white rounded-full border border-gray-200"></div>
                        <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white rounded-full border border-gray-200"></div>

                        <div class="flex items-start justify-between">
                            <div class="flex-1 pr-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $order->concert->title ?? 'Concert' }}</h3>

                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mt-2">
                                    <div>
                                        <div class="font-medium text-gray-700">Reference:</div>
                                        <div class="font-mono text-blue-600 text-xs">{{ $order->reference_code }}</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-700">Tanggal:</div>
                                        <div class="text-xs">{{ \Carbon\Carbon::parse($order->concert->date)->format('d M Y H:i') }}</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-700">Qty</div>
                                        <div class="text-xs">{{ $order->items->sum('quantity') }} tiket</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-700">Total</div>
                                        <div class="text-green-600 font-semibold text-sm">Rp {{ number_format($order->total_amount - ($order->discount_amount ?? 0), 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-800">
                                        ✓ Dibayar
                                    </span>
                                </div>
                            </div>

                            <div class="flex-shrink-0 ml-2 flex items-center">
                                <a href="{{ route('history.show', $order->id) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 mt-4 pt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Detail Tiket:</p>
                            <div class="space-y-1">
                                @foreach ($order->items as $item)
                                    <div class="bg-gray-50 p-2 rounded-md flex items-center justify-between text-sm">
                                        <div class="font-medium text-gray-900">{{ $item->ticketType->name }}</div>
                                        <div class="text-gray-600">x{{ $item->quantity }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination (numbered with jump by 5) -->
            @if($orders->hasPages())
                @php
                    $current = $orders->currentPage();
                    $last = $orders->lastPage();
                    $start = max(1, min($current - 2, max(1, $last - 4)));
                    $end = min($last, max($current + 2, min(5, $last)));
                @endphp

                <nav class="mt-6 flex items-center justify-center gap-2 text-sm" aria-label="Pagination">
                    {{-- jump back 5 --}}
                    @php $jumpBack = max(1, $current - 5); @endphp
                    <a href="{{ $orders->url($jumpBack) }}" class="text-gray-500 hover:text-gray-700 px-2 py-1 rounded">««</a>

                    {{-- previous --}}
                    @if($orders->onFirstPage())
                        <span class="text-gray-300 px-2 py-1">‹</span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="text-gray-500 hover:text-gray-700 px-2 py-1 rounded">‹</a>
                    @endif

                    {{-- page numbers --}}
                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $current)
                            <span class="px-3 py-1 font-medium text-gray-900">{{ $i }}</span>
                        @else
                            <a href="{{ $orders->url($i) }}" class="text-gray-600 hover:bg-gray-100 px-3 py-1 rounded">{{ $i }}</a>
                        @endif
                    @endfor

                    {{-- next --}}
                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="text-gray-500 hover:text-gray-700 px-2 py-1 rounded">›</a>
                    @else
                        <span class="text-gray-300 px-2 py-1">›</span>
                    @endif

                    {{-- jump forward 5 --}}
                    @php $jumpForward = min($last, $current + 5); @endphp
                    <a href="{{ $orders->url($jumpForward) }}" class="text-gray-500 hover:text-gray-700 px-2 py-1 rounded">»»</a>
                </nav>
            @endif
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

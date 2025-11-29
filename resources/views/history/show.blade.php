@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('history') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Riwayat
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail Tiket</h1>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Concert Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-6">
            <h2 class="text-2xl font-bold">{{ $order->concert->name }}</h2>
            <p class="text-purple-100 mt-1">{{ $order->concert->location ?? 'Lokasi tidak tersedia' }}</p>
            <p class="text-purple-100 mt-2">
                📅 {{ \Carbon\Carbon::parse($order->concert->date)->format('l, d F Y') }} 
                🕐 {{ \Carbon\Carbon::parse($order->concert->date)->format('H:i') }}
            </p>
        </div>

        <!-- Ticket Info Section -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left: Ticket Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Informasi Tiket</h3>

                    <!-- Reference Code -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <label class="text-sm font-medium text-gray-700">Reference Code (Nomor Tiket)</label>
                        <p class="text-2xl font-mono font-bold text-blue-600 mt-2 break-all">{{ $referenceCode }}</p>
                        <p class="text-xs text-gray-500 mt-2">Gunakan kode ini untuk masuk ke acara</p>
                    </div>

                    <!-- Buyer Details -->
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nama Pembeli</label>
                            <p class="text-gray-900 font-medium mt-1">{{ $order->buyer_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <p class="text-gray-900 font-medium mt-1">{{ $order->buyer_email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Telepon</label>
                            <p class="text-gray-900 font-medium mt-1">{{ $order->buyer_phone ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium text-green-800">Pembayaran Berhasil</span>
                        </div>
                        <p class="text-sm text-green-700 mt-2">
                            Tanggal Pembayaran: {{ $order->paid_at->format('d M Y H:i') ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Right: QR Code & Summary -->
                <div class="flex flex-col items-center">
                    <!-- QR Code Section -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6 w-full">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">QR Code Tiket</h3>
                        <div class="flex justify-center bg-white p-4 rounded border border-gray-200">
                            {!! $qrCode !!}
                        </div>
                        <p class="text-center text-sm text-gray-600 mt-3">
                            Tunjukkan QR Code ini saat masuk acara
                        </p>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 w-full">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h3>
                        
                        <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between items-start text-sm">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->ticketType->name }}</p>
                                        <p class="text-gray-500">Jumlah: {{ $item->quantity }} tiket</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                        <p class="text-gray-500 text-xs">Rp {{ number_format($item->price, 0, ',', '.') }}/tiket</p>
                                    </div>
                                </div>
                                <div class="border-t border-gray-200"></div>
                            @endforeach
                        </div>

                        <div class="flex justify-between items-center pt-2 text-lg font-bold text-gray-900">
                            <span>Total:</span>
                            <span class="text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Section -->
        <div class="border-t border-gray-200 bg-gray-50 p-6">
            <div class="flex flex-col md:flex-row gap-3">
                <button onclick="window.print()" 
                        class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm-6-4a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Cetak Tiket
                </button>

                <a href="{{ route('purchase.detail', $order->id) }}"
                   class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Detail Pesanan
                </a>

                <a href="{{ route('history') }}"
                   class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <div class="mt-8 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
        <h3 class="text-lg font-semibold text-yellow-900 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            Informasi Penting
        </h3>
        <ul class="mt-3 text-sm text-yellow-800 space-y-2">
            <li>✓ Simpan reference code Anda dengan baik</li>
            <li>✓ Tunjukkan QR code ini saat check-in di acara</li>
            <li>✓ Jika ada masalah, hubungi customer service dengan reference code Anda</li>
            <li>✓ Tiket tidak bisa ditransfer atau dikembalikan</li>
        </ul>
    </div>
</div>

<style>
    @media print {
        body {
            background: white;
        }
        .no-print {
            display: none;
        }
    }
</style>
@endsection

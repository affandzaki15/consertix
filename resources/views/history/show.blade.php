@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    
    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- QR Code Section -->
        <div class="p-8 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 text-center">QR Code Tiket</h2>

            @php
                $uniqueQrUrls = isset($tickets) && count($tickets) > 0 ? $tickets->pluck('qr_code_url')->filter()->unique() : collect();
                $qrUrl = $uniqueQrUrls->count() > 0 ? $uniqueQrUrls->first() : null;
            @endphp

            @if(isset($order) && $order)
                <div class="flex flex-col items-center">
                    @if($qrUrl && !empty($qrUrl))
                        <!-- Display stored QR code -->
                        <img src="{{ $qrUrl }}" alt="QR for order {{ $order->reference_code }}" class="w-56 h-56 object-contain mb-6" onerror="this.parentElement.style.display='none'; document.getElementById('dynamicQr').style.display='flex';" />
                        <div id="dynamicQr" class="w-56 h-56 items-center justify-center bg-gray-100 rounded-lg mb-6" style="display: none;">
                            <div id="qrCode" style="width: 224px; height: 224px;"></div>
                        </div>
                    @else
                        <!-- Generate QR code dynamically if not stored -->
                        <div class="w-56 h-56 flex items-center justify-center bg-gray-100 rounded-lg mb-6">
                            <div id="qrCode" style="width: 224px; height: 224px;"></div>
                        </div>
                    @endif

                    <p class="text-sm text-gray-600 mb-2">Tunjukkan QR Code ini saat check-in</p>

                    <!-- Judul Tiket (gunakan nama konser) -->
                    <div class="w-full mt-3 p-3 bg-white rounded-lg border border-gray-100 text-center">
                        <p class="text-xs text-gray-500">Judul Tiket</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $order->concert->title ?? 'N/A' }}</p>
                    </div>

                    <!-- Daftar Jenis Tiket -->
                    <div class="w-full mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-3">Jenis Tiket:</p>
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700">{{ $item->ticketType->name }}</span>
                                    <span class="font-semibold text-gray-900">{{ $item->quantity }} tiket</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                @if(!$qrUrl || empty($qrUrl))
                <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const qrContainer = document.getElementById('qrCode');
                        if (qrContainer && qrContainer.children.length === 0) {
                            const qrData = JSON.stringify({
                                reference_code: '{{ $order->reference_code }}',
                                order_id: {{ $order->id }},
                                concert: '{{ $order->concert->title ?? '' }}',
                            });
                            
                            new QRCode(qrContainer, {
                                text: qrData,
                                width: 224,
                                height: 224,
                                colorDark: '#000000',
                                colorLight: '#ffffff',
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        }
                    });
                </script>
                @endif
            @endif
        </div>

        <!-- Order Summary -->
        <div class="p-8 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Rincian Pesanan</h2>
            
            <div class="space-y-3 mb-6">
                @foreach ($order->items as $item)
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->ticketType->name }}</p>
                            <p class="text-sm text-gray-600">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-semibold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600 mt-2">
                    <span>Tax & Fee (25%)</span>
                    <span>Rp {{ number_format($order->total_amount * 0.25, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-lg font-bold text-gray-900 mt-4 pt-4 border-t border-gray-200">
                    <span>Total Bayar</span>
                    <span class="text-indigo-600">Rp {{ number_format($order->total_amount * 1.25, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Reference Code -->
        <div class="p-8 bg-indigo-50 border-b border-indigo-200">
            <p class="text-sm text-gray-700">Nomor Referensi Pesanan</p>
            <p class="text-2xl font-mono font-bold text-indigo-600 mt-2">{{ $order->reference_code }}</p>
        </div>

        <!-- Actions Section -->
        <div class="p-6 flex gap-3">
            <a href="{{ route('history.print', $order->id) }}" target="_blank"
               class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm-6-4a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Cetak Tiket
            </a>

            <a href="{{ route('history') }}"
               class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

@endsection

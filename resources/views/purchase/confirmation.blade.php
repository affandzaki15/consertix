@extends('layouts.app')

@section('content')
<div class="w-full bg-gray-50 py-10 font-poppins">
    <div class="max-w-6xl mx-auto px-6">
        <!-- Step Indicator -->
        <div class="flex items-center space-x-3 text-gray-500 text-sm mb-12">
            <span class="flex items-center gap-2 text-gray-600">
                <div class="w-7 h-7 bg-gray-300 text-white flex items-center justify-center rounded-full">1</div>
                Pilih Kategori
            </span>
            <span>›</span>
            <span class="flex items-center gap-2 text-gray-600">
                <div class="w-7 h-7 bg-gray-300 text-white flex items-center justify-center rounded-full">2</div>
                Detail Pesanan
            </span>
            <span>›</span>
            <span class="flex items-center gap-2 text-gray-600">
                <div class="w-7 h-7 bg-gray-300 text-white flex items-center justify-center rounded-full">3</div>
                Metode Pembayaran
            </span>
            <span>›</span>
            <span class="flex items-center gap-2 text-indigo-600 font-semibold">
                <div class="w-7 h-7 bg-indigo-600 text-white flex items-center justify-center rounded-full">4</div>
                Pembayaran
            </span>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left: Payment Instructions -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-3xl shadow-md p-8 text-center">
                    <p class="text-gray-600 mb-4">Sisa waktu pembayaran</p>
                    <div class="text-5xl font-bold text-indigo-600 mb-6 font-mono" id="countdown">00:30:00</div>

                    <p class="text-gray-700 mb-2">Silahkan lakukan pembayaran sebelum</p>
                    <p class="text-lg font-semibold text-gray-900 mb-6">{{ now()->addMinutes(30)->format('d F Y H:i') }}</p>

                    <p class="text-gray-600 text-sm mb-8">
                        Jika meleawati batas waktu tersebut, pesanan akan dibatalkan secara otomatis
                    </p>

                    <div class="space-y-3">
                        <button type="button" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold">
                            Bayar Sekarang
                        </button>
                        <button type="button" class="w-full py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-semibold">
                            Batalkan Pesanan
                        </button>
                    </div>
                </div>

                <!-- Payment Method Info -->
                <div class="bg-white rounded-3xl shadow-md p-8 mt-8">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/BCA_logo.svg/1200px-BCA_logo.svg.png" alt="Mandiri" class="h-8">
                        <span class="font-semibold text-lg">Bank Transfer - {{ strtoupper($order->payment_method ?? 'N/A') }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Status Pemesanan</p>
                            <p class="text-orange-600 font-semibold">{{ strtoupper($order->status) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">ID Pesanan</p>
                            <p class="font-semibold text-gray-900">{{ $order->id }}</p>
                        </div>
                    </div>

                    <hr class="my-6">

                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-gray-900">Rp{{ number_format($order->total_amount * 1.25) }}</p>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary Card -->
            <div class="bg-white rounded-3xl shadow-lg p-6 border h-fit">
                <div class="mb-6">
                    @if($concert->image_url)
                        <img src="{{ $concert->image_url }}" alt="{{ $concert->title }}" class="w-full h-40 object-cover rounded-xl mb-4">
                    @endif
                    <h3 class="text-lg font-bold text-gray-900">{{ $concert->title }}</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        {{ \Carbon\Carbon::parse($concert->date)->format('d F Y') }} • {{ $concert->time ?? '19:00 – 22:00' }}
                    </p>
                    <p class="text-gray-600 text-sm">{{ $concert->location }}</p>
                </div>

                <hr class="my-4">

                <div class="space-y-3 mb-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">{{ $item->ticketType->name }} x{{ $item->quantity }}</span>
                            <span class="font-semibold">Rp{{ number_format($item->price * $item->quantity) }}</span>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <div class="flex justify-between text-gray-600 text-sm mb-2">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($order->total_amount) }}</span>
                </div>

                <div class="flex justify-between text-gray-600 text-sm mb-2">
                    <span>Local Tax (20%)</span>
                    <span>Rp{{ number_format($order->total_amount * 0.20) }}</span>
                </div>

                <div class="flex justify-between text-gray-600 text-sm mb-4">
                    <span>Service Fee (5%)</span>
                    <span>Rp{{ number_format($order->total_amount * 0.05) }}</span>
                </div>

                <hr class="my-4">

                <div class="flex justify-between font-semibold text-gray-900 text-lg">
                    <span>Grand Total</span>
                    <span>Rp{{ number_format($order->total_amount * 1.25) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('countdown');
    let timeLeft = 30 * 60; // 30 minutes in seconds

    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    countdownElement.textContent = formatTime(timeLeft);

    const timer = setInterval(() => {
        timeLeft--;
        countdownElement.textContent = formatTime(timeLeft);

        if (timeLeft <= 0) {
            clearInterval(timer);
            alert('Waktu pembayaran telah habis. Pesanan dibatalkan.');
            // Optionally redirect or handle expiration
        }
    }, 1000);
});
</script>

@endsection

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
                    <p id="countdownLabel" class="text-gray-600 mb-4">Sisa waktu pembayaran</p>
                    <div class="text-5xl font-bold text-indigo-600 mb-6 font-mono" id="countdown">00:30:00</div>

                    <p class="text-gray-700 mb-2">Silahkan lakukan pembayaran sebelum</p>
                    <p class="text-lg font-semibold text-gray-900 mb-6">{{ \Carbon\Carbon::now('Asia/Jakarta')->addMinutes(30)->format('d F Y H:i') }}</p>

                    <p class="text-gray-600 text-sm mb-8">
                        Jika meleawati batas waktu tersebut, pesanan akan dibatalkan secara otomatis
                    </p>

                    <div class="space-y-3" id="paymentActions">
                        @if(strtolower($order->status) !== 'paid')
                            <button id="bayarSekarangBtn" type="button" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold">
                                Bayar Sekarang
                            </button>
                            <button id="cancelOrderBtn" type="button" class="w-full py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-semibold">
                                Batalkan Pesanan
                            </button>
                        @else
                            <div class="w-full py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 font-semibold text-center">
                                Pesanan Sudah Dibayar
                            </div>
                            <a href="{{ route('history.show', $order->id) }}" class="block w-full py-3 text-center rounded-xl bg-white border border-gray-200 text-indigo-600 font-semibold">Lihat Tiket</a>
                        @endif
                    </div>
                </div>

                <!-- Payment Method Info -->
                <div class="bg-white rounded-3xl shadow-md p-8 mt-8">
                    <div class="flex items-center gap-3 mb-6">
                        @php
                            $method = strtolower(trim($order->payment_method ?? ''));
                            $paymentLogos = [
                                'bca' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbca.png&w=320&q=50',
                                'mandiri' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fmandiri.png&w=320&q=50',
                                'bri' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbri.png&w=320&q=50',
                                'bni' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbni.png&w=320&q=50',
                                'ovo' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fovo.png&w=320&q=50',
                                'gopay' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fgopay.png&w=320&q=50',
                                'dana' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fdana.png&w=320&q=50',
                                'qris' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fqris.png&w=320&q=50',
                            ];
                            $logoUrl = $paymentLogos[$method] ?? null;
                        @endphp

                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $order->payment_method }}" class="h-8">
                        @else
                            <div class="h-8 w-24 bg-gray-100 rounded flex items-center justify-center text-sm text-gray-600">{{ strtoupper($order->payment_method ?? 'PAY') }}</div>
                        @endif

                        <span class="font-semibold text-lg"> Pembayaran {{ strtoupper($order->payment_method ?? 'N/A') }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Status Pemesanan</p>
                            <p id="orderStatus" class="text-orange-600 font-semibold">{{ strtoupper($order->status) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">ID Pesanan</p>
                            <p class="font-semibold text-gray-900">{{ $order->id }}</p>
                        </div>
                    </div>

                    <hr class="my-6">

                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-gray-900">Rp{{ number_format($order->total_amount - ($order->discount_amount ?? 0)) }}</p>
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

                @if($order->discount_amount > 0)
                <div class="flex justify-between text-green-600 text-sm mb-2">
                    <span>Diskon</span>
                    <span>-Rp{{ number_format($order->discount_amount) }}</span>
                </div>
                @endif

                <hr class="my-4">

                <div class="flex justify-between font-semibold text-gray-900 text-lg">
                    <span>Total</span>
                    <span>Rp{{ number_format($order->total_amount - ($order->discount_amount ?? 0)) }}</span>
</div>
</div>

<!-- Midtrans-like Payment Modal -->
<div id="midtransModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4" aria-hidden="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity"></div>

    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto z-10 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold">Payment Gateway</h2>
                <i class="fa-solid fa-lock text-2xl"></i>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Payment Method Display -->
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-600 mb-2">Payment Method</p>
                @php
                    $modalMethod = strtolower(trim($order->payment_method ?? ''));
                    $modalLogos = [
                        'bca' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbca.png&w=320&q=50',
                                'mandiri' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fmandiri.png&w=320&q=50',
                                'bri' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbri.png&w=320&q=50',
                                'bni' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbni.png&w=320&q=50',
                                'ovo' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fovo.png&w=320&q=50',
                                'gopay' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fgopay.png&w=320&q=50',
                                'dana' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fdana.png&w=320&q=50',
                                'qris' => 'https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fqris.png&w=320&q=50',
                    ];
                    $modalLogoUrl = $modalLogos[$modalMethod] ?? null;
                @endphp
                <div class="flex items-center gap-3">
                    @if($modalLogoUrl)
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border">
                            <img src="{{ $modalLogoUrl }}" alt="{{ $order->payment_method }}" class="h-8 object-contain">
                        </div>
                    @else
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-building-columns text-indigo-600 text-xl"></i>
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-900 capitalize" id="selectedMethodName">{{ $order->payment_method ?? '-' }}</p>
                        <p class="text-xs text-gray-500">Secure Payment</p>
                    </div>
                </div>
            </div>

            <!-- Amount Display -->
            <div class="border-t border-b py-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium" id="modalSubtotal">Rp{{ number_format($order->total_amount) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between items-center mb-2">
                    <span class="text-green-600">Diskon</span>
                    <span class="font-medium text-green-600" id="modalDiscount">-Rp{{ number_format($order->discount_amount) }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center pt-2 border-t">
                    <span class="font-bold text-gray-900">Total</span>
                    <span class="font-bold text-xl text-indigo-600" id="modalTotal">Rp{{ number_format($order->total_amount - ($order->discount_amount ?? 0)) }}</span>
                </div>
            </div>

            <!-- Processing Animation -->
            <div id="processingState" class="text-center space-y-4">
                <div class="flex justify-center">
                    <div class="inline-block">
                        <i class="fa-solid fa-spinner text-4xl text-indigo-600 animate-spin"></i>
                    </div>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Processing Payment</p>
                    <p class="text-sm text-gray-500 mt-1">Please wait while we process your payment...</p>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div id="progressBar" class="bg-indigo-600 h-2 rounded-full" style="width: 0%; transition: width 2s ease;"></div>
                </div>
            </div>

            <!-- Success State -->
                <div id="successState" class="text-center space-y-4 hidden">
                <div class="flex justify-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center relative">
                        <i class="fa-solid fa-check text-4xl text-green-600"></i>
                    </div>
                </div>
                @if(isset($modalLogoUrl) && $modalLogoUrl)
                    <div class="flex justify-center">
                        <img src="{{ $modalLogoUrl }}" alt="{{ $order->payment_method }}" class="h-10 mt-2 object-contain">
                    </div>
                @endif
                <div>
                    <p class="font-semibold text-gray-900">Payment Successful!</p>
                    <p class="text-sm text-gray-500 mt-1">Your payment has been processed</p>
                </div>
                <p id="transactionId" class="text-xs text-gray-400">Transaction ID: -</p>
            </div>

            <!-- Action Buttons -->
            <div id="processingButtons" class="flex gap-3">
                <button id="cancelPaymentBtn" type="button" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50">Cancel</button>
            </div>

            <div id="successButtons" class="hidden">
                <button id="completePaymentBtn" type="button" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">Complete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timer countdown (persist across navigation using localStorage per-order)
    const countdownElement = document.getElementById('countdown');
    const STORAGE_KEY = 'payment_timer_order_{{ $order->id }}';
    const SERVER_EXPIRES = {{ \Carbon\Carbon::now('Asia/Jakarta')->addMinutes(30)->valueOf() }}; // ms since epoch in WIB

    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    // Restore expires timestamp from localStorage or use server-provided WIB expiry
    if (!window.paymentExpires) {
        const stored = localStorage.getItem(STORAGE_KEY);
        const now = Date.now();
        if (stored && !isNaN(parseInt(stored))) {
            const expires = parseInt(stored, 10);
            if (expires > now) {
                window.paymentExpires = expires;
            } else {
                // expired value, clear it and fall back to server expiry or now + 30min
                localStorage.removeItem(STORAGE_KEY);
                if (SERVER_EXPIRES && SERVER_EXPIRES > now) {
                    window.paymentExpires = SERVER_EXPIRES;
                } else {
                    window.paymentExpires = now + 30 * 60 * 1000;
                }
                localStorage.setItem(STORAGE_KEY, window.paymentExpires);
            }
        } else {
            if (SERVER_EXPIRES && SERVER_EXPIRES > now) {
                window.paymentExpires = SERVER_EXPIRES;
            } else {
                window.paymentExpires = now + 30 * 60 * 1000;
            }
            localStorage.setItem(STORAGE_KEY, window.paymentExpires);
        }
    }

    // compute remaining seconds helper
    function getRemainingSeconds() {
        return Math.max(0, Math.ceil((window.paymentExpires - Date.now()) / 1000));
    }

    // initial render
    if (countdownElement) countdownElement.textContent = formatTime(getRemainingSeconds());

    // Start interval only once per page session
    if (!window.paymentTimer) {
        window.paymentTimer = setInterval(() => {
            const secs = getRemainingSeconds();
            if (countdownElement) countdownElement.textContent = formatTime(secs);

            // persist expiry so it survives navigation/reload
            try { localStorage.setItem(STORAGE_KEY, window.paymentExpires); } catch(e) {}

            if (secs <= 0) {
                clearInterval(window.paymentTimer);
                window.paymentTimer = null;
                try { localStorage.removeItem(STORAGE_KEY); } catch(e) {}
                alert('Waktu pembayaran telah habis. Pesanan dibatalkan.');
            }
        }, 1000);
    }

    // save expiry when leaving the page
    window.addEventListener('beforeunload', function() {
        try { localStorage.setItem(STORAGE_KEY, window.paymentExpires); } catch(e) {}
    });

    // keep saved expiry in sync when user hides the page
    document.addEventListener('visibilitychange', function() {
        try { localStorage.setItem(STORAGE_KEY, window.paymentExpires); } catch(e) {}
    });

    // Midtrans Modal Logic
    const midtransModal = document.getElementById('midtransModal');
    const bayarSekarangBtn = document.getElementById('bayarSekarangBtn');
    const cancelPaymentBtn = document.getElementById('cancelPaymentBtn');
    const completePaymentBtn = document.getElementById('completePaymentBtn');
    const processingState = document.getElementById('processingState');
    const successState = document.getElementById('successState');
    const processingButtons = document.getElementById('processingButtons');
    const successButtons = document.getElementById('successButtons');
    const progressBar = document.getElementById('progressBar');

    function showMidtransModal() {
        midtransModal.classList.remove('hidden');
        midtransModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Reset states
        progressBar.style.width = '0%';
        processingState.classList.remove('hidden');
        successState.classList.add('hidden');
        processingButtons.classList.remove('hidden');
        successButtons.classList.add('hidden');

        // Start payment processing simulation
        startPaymentProcessing();
    }

    function hideMidtransModal() {
        midtransModal.classList.add('hidden');
        midtransModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function startPaymentProcessing() {
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                completePaymentProcessing();
            }
            progressBar.style.width = progress + '%';
        }, 300);
    }

    function completePaymentProcessing() {
        setTimeout(() => {
            processingState.classList.add('hidden');
            successState.classList.remove('hidden');
            processingButtons.classList.add('hidden');
            successButtons.classList.remove('hidden');

            const transactionId = 'TXN' + Date.now();
            document.getElementById('transactionId').textContent = 'Transaction ID: ' + transactionId;
        }, 1000);
    }

    bayarSekarangBtn.addEventListener('click', showMidtransModal);
    cancelPaymentBtn.addEventListener('click', hideMidtransModal);
    
    // Cancel Order button (bottom section)
    const cancelOrderBtn = document.getElementById('cancelOrderBtn');
    if (cancelOrderBtn) {
        cancelOrderBtn.addEventListener('click', function() {
            if (!confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                return;
            }

            const url = "{{ route('purchase.cancel', $order->id) }}";
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.success) {
                    // Stop countdown timer
                    try { clearInterval(window.paymentTimer); window.paymentTimer = null; } catch(e) {}
                    try { localStorage.removeItem(STORAGE_KEY); } catch(e) {}
                    // Redirect to home page
                    window.location.href = '/';
                } else {
                    alert('Gagal membatalkan pesanan: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan saat membatalkan pesanan.');
            });
        });
    }
    
    completePaymentBtn.addEventListener('click', function() {
        // Send request to mark order as paid
        const url = "{{ route('purchase.complete', $order->id) }}";
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token || ''
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
                if (data && data.success) {
                // update cart badge immediately with remaining cart count
                if (typeof updateCartBadge === 'function' && data.cart_count !== undefined) {
                    updateCartBadge(data.cart_count);
                }

                // update status text and color
                const statusEl = document.getElementById('orderStatus');
                if (statusEl) {
                    statusEl.textContent = (data.status || 'paid').toString().toUpperCase();
                    statusEl.classList.remove('text-orange-600');
                    statusEl.classList.add('text-green-600');
                }

                // disable bayar button
                if (bayarSekarangBtn) {
                    bayarSekarangBtn.disabled = true;
                    bayarSekarangBtn.classList.remove('bg-indigo-600');
                    bayarSekarangBtn.classList.add('bg-gray-300', 'text-white');
                    bayarSekarangBtn.textContent = 'Sudah Dibayar';
                }

                // close modal and show success state if not already
                    hideMidtransModal();

                    // stop countdown timer
                    try { clearInterval(window.paymentTimer); window.paymentTimer = null; } catch(e) {}

                    // update countdown label and display a green check
                    const countdownLabel = document.getElementById('countdownLabel');
                    const countdownEl = document.getElementById('countdown');
                    if (countdownLabel) {
                        countdownLabel.textContent = 'Pembayaran Selesai';
                        countdownLabel.classList.remove('text-gray-600');
                        countdownLabel.classList.add('text-green-600');
                    }
                    if (countdownEl) {
                        countdownEl.innerHTML = '<span class="inline-flex items-center gap-3"><i class="fa-solid fa-circle-check text-6xl text-green-600"></i></span>';
                        countdownEl.classList.remove('text-indigo-600');
                        countdownEl.classList.add('text-green-600');
                    }

                    // disable cancel order button
                    const cancelOrderBtn = document.getElementById('cancelOrderBtn');
                    if (cancelOrderBtn) {
                        cancelOrderBtn.disabled = true;
                        cancelOrderBtn.classList.remove('bg-red-50');
                        cancelOrderBtn.classList.add('bg-gray-200', 'text-gray-600');
                        cancelOrderBtn.textContent = 'Pesanan Selesai';
                    }

                    // Replace main payment action area with paid confirmation and link to ticket
                    var paymentActions = document.getElementById('paymentActions');
                    if (paymentActions) {
                        var ticketUrl = "{{ route('history.show', $order->id) }}";
                        paymentActions.innerHTML = '' +
                            '<div class="w-full py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 font-semibold text-center">Pesanan Sudah Dibayar</div>' +
                            '<a href="' + ticketUrl + '" class="block w-full py-3 text-center rounded-xl bg-white border border-gray-200 text-indigo-600 font-semibold mt-2">Lihat Tiket</a>';

                        // remove persisted timer for this order so it won't reappear
                        try { localStorage.removeItem(STORAGE_KEY); } catch(e) {}
                    }
            } else {
                alert('Gagal menandai pembayaran. Silakan coba lagi.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat memproses pembayaran.');
        });
    });
});
</script>

@endsection

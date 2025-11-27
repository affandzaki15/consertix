@extends('layouts.app')

@section('content')
<div class="w-full bg-gray-50 py-10 font-poppins">
    <div class="max-w-6xl mx-auto px-6 mb-6">
        <div class="flex items-center space-x-3 text-gray-500 text-sm">
            <span class="flex items-center gap-2 text-indigo-600 font-semibold">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-600 flex items-center justify-center rounded-full">1</div>
                Choose Category
            </span>
            <span>›</span>
            <span class="flex items-center gap-2 text-indigo-600 font-semibold">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-600 flex items-center justify-center rounded-full">2</div>
                Order Details
            </span>
            <span>›</span>
            <span class="flex items-center gap-2 text-indigo-600 font-semibold">
                <div class="w-7 h-7 bg-indigo-600 text-white flex items-center justify-center rounded-full">3</div>
                Payment Method
            </span>
            <span>›</span>
            <span>4. Payment</span>
        </div>
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-6">
        <div class="md:col-span-2 space-y-6">

            <div class="">
                <h1 class="text-3xl font-bold text-gray-900">{{ $concert->title }}</h1>
                <p class="text-gray-600 mt-1">{{ \Carbon\Carbon::parse($concert->date)->format('d F Y') }} • {{ $concert->time ?? '19:00 – 22:00' }}</p>
                <p class="text-gray-600">{{ $concert->location }}</p>
            </div>

            <form id="paymentSelectionForm" action="{{ route('purchase.pay', $order->id) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Bank Transfer -->
                <div class="bg-white rounded-2xl p-6 border">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div>
                                <div class="font-semibold">Bank Transfer</div>
                                <div class="text-sm text-gray-500">Transfer via bank</div>
                            </div>
                        </div>
                        <div>
                            <button type="button" data-method="bank_transfer" class="select-method inline-flex items-center gap-2 px-3 py-2 border rounded-lg">
                                Pilih
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-3 items-center text-sm text-gray-600">
                        <img src="/logo/bca.png" alt="bca" class="h-6" />
                        <img src="/logo/bni.png" alt="bni" class="h-6" />
                        <img src="/logo/mandiri.png" alt="mandiri" class="h-6" />
                        <!-- logos as available -->
                    </div>
                </div>

                <!-- E-Wallet / QRIS -->
                <div class="bg-white rounded-2xl p-6 border">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <div>
                                <div class="font-semibold">E-Wallet / QRIS</div>
                                <div class="text-sm text-gray-500">OVO, DANA, Gopay, etc.</div>
                            </div>
                        </div>
                        <div>
                            <button type="button" data-method="ewallet" class="select-method inline-flex items-center gap-2 px-3 py-2 border rounded-lg">
                                Pilih
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-3 items-center">
                        <img src="/logo/ovo.png" alt="ovo" class="h-6" />
                        <img src="/logo/dana.png" alt="dana" class="h-6" />
                        <img src="/logo/gopay.png" alt="gopay" class="h-6" />
                    </div>
                </div>

                <!-- Credit Card -->
                <div class="bg-white rounded-2xl p-6 border">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i class="fa-regular fa-credit-card"></i>
                            </div>
                            <div>
                                <div class="font-semibold">Credit Card</div>
                                <div class="text-sm text-gray-500">Visa, Mastercard, JCB</div>
                            </div>
                        </div>
                        <div>
                            <button type="button" data-method="card" class="select-method inline-flex items-center gap-2 px-3 py-2 border rounded-lg">
                                Pilih
                            </button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="p-4 border rounded-lg text-center text-sm text-gray-600">Credit Card</div>
                    </div>
                </div>

            </form>

        </div>

        <div class="bg-white shadow-lg rounded-3xl p-6 border h-fit">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-600 flex items-center justify-center rounded-lg">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Order Summary</h3>
            </div>

            @foreach($order->items as $item)
                <div class="mb-3">
                    <div class="flex justify-between font-medium text-gray-900">
                        <span>{{ $item->ticketType->name }}</span>
                        <span>Rp{{ number_format($item->price) }}</span>
                    </div>
                    <p class="text-sm text-gray-500">x{{ $item->quantity }}</p>
                </div>
            @endforeach

            <hr class="my-4">

            <div class="flex justify-between text-gray-600 text-sm mb-2">
                <span>Subtotal</span>
                <span>Rp{{ number_format($order->total_amount) }}</span>
            </div>

            <div class="flex justify-between font-semibold text-gray-900 text-lg mb-4">
                <span>Grand Total</span>
                <span>Rp{{ number_format($order->total_amount) }}</span>
            </div>

            <div class="mt-4">
                <div class="mb-3">
                    <label class="inline-flex items-center">
                        <input id="agreeTerms" type="checkbox" class="mr-2">
                        <span class="text-sm text-gray-600">I agree <a href="#" class="text-indigo-600">Terms & Conditions</a></span>
                    </label>
                </div>

                <div class="mb-3">
                    <label class="inline-flex items-center">
                        <input id="agreePrivacy" type="checkbox" class="mr-2">
                        <span class="text-sm text-gray-600">I agree <a href="#" class="text-indigo-600">Privacy Policy & Data Processing</a></span>
                    </label>
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <a href="{{ route('purchase.detail', $order->id) }}" class="w-14 h-12 border rounded-xl flex items-center justify-center text-gray-600 hover:bg-gray-100">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>

                    <button id="payNowBtn" disabled class="flex-1 py-3 bg-indigo-200 text-white rounded-xl font-semibold">Pay Now</button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const methodButtons = document.querySelectorAll('.select-method');
    const payBtn = document.getElementById('payNowBtn');
    const agreeTerms = document.getElementById('agreeTerms');
    const agreePrivacy = document.getElementById('agreePrivacy');
    const form = document.getElementById('paymentSelectionForm');

    let selectedMethod = null;

    function updatePayState() {
        if (selectedMethod && agreeTerms.checked && agreePrivacy.checked) {
            payBtn.disabled = false;
            payBtn.classList.remove('bg-indigo-200');
            payBtn.classList.add('bg-indigo-600');
        } else {
            payBtn.disabled = true;
            payBtn.classList.remove('bg-indigo-600');
            payBtn.classList.add('bg-indigo-200');
        }
    }

    methodButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            // clear previous selection styling
            methodButtons.forEach(b => b.classList.remove('bg-indigo-600','text-white'));
            btn.classList.add('bg-indigo-600','text-white');
            selectedMethod = btn.getAttribute('data-method');
            updatePayState();
        });
    });

    agreeTerms.addEventListener('change', updatePayState);
    agreePrivacy.addEventListener('change', updatePayState);

    payBtn.addEventListener('click', function () {
        if (!selectedMethod) return alert('Pilih metode pembayaran dulu.');
        // create a hidden input and submit
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'payment_method';
        input.value = selectedMethod;
        form.appendChild(input);
        form.submit();
    });
});
</script>

@endsection

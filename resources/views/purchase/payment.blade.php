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
                <div class="bg-white rounded-2xl border overflow-hidden">
                    <button type="button" class="payment-method-toggle w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50" data-target="bank-transfer">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div class="text-left">
                                <div class="font-semibold">Bank Transfer</div>
                                <div class="text-sm text-gray-500">Transfer via bank</div>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-down toggle-icon transition-transform"></i>
                    </button>

                    <div id="bank-transfer" class="payment-method-content hidden border-t px-6 py-6">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option" data-method="bca">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/BCA_logo.svg/1200px-BCA_logo.svg.png" alt="BCA" class="h-12">
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option" data-method="bjb">
                                <span class="font-bold text-sm">bank bjb</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option" data-method="bni">
                                <span class="font-bold text-sm">BNI</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option" data-method="bri">
                                <span class="font-bold text-sm">BANK BRI</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option" data-method="bsi">
                                <span class="font-bold text-sm">BSI</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option" data-method="sampoerna">
                                <span class="font-bold text-sm">Bank Sampoerna</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option" data-method="cimb">
                                <span class="font-bold text-sm">CIMB NIAGA</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option" data-method="mandiri">
                                <span class="font-bold text-sm">mandiri</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option" data-method="permata">
                                <span class="font-bold text-sm">Permata Bank</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- E-Wallet / QRIS -->
                <div class="bg-white rounded-2xl border overflow-hidden mt-6">
                    <button type="button" class="payment-method-toggle w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50" data-target="ewallet">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <div class="text-left">
                                <div class="font-semibold">E-Wallet / QRIS</div>
                                <div class="text-sm text-gray-500">OVO, DANA, Gopay, etc.</div>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-down toggle-icon transition-transform"></i>
                    </button>

                    <div id="ewallet" class="payment-method-content hidden border-t px-6 py-6">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option" data-method="ovo">
                                <span class="font-bold text-sm">OVO</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option" data-method="dana">
                                <span class="font-bold text-sm">DANA</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option" data-method="qris">
                                <span class="font-bold text-sm">QRIS</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option" data-method="gopay">
                                <span class="font-bold text-sm">Gopay</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option" data-method="linkaja">
                                <span class="font-bold text-sm">Link Aja!</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option" data-method="astrapay">
                                <span class="font-bold text-sm">AstraPay</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option" data-method="jeniuspay">
                                <span class="font-bold text-sm">Jenius Pay</span>
                            </div>
                            <div class="border rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option" data-method="shopeepay">
                                <span class="font-bold text-sm">ShopeePay</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credit Card -->
                <div class="bg-white rounded-2xl border overflow-hidden mt-6">
                    <button type="button" class="payment-method-toggle w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50" data-target="card">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i class="fa-regular fa-credit-card"></i>
                            </div>
                            <div class="text-left">
                                <div class="font-semibold">Credit Card</div>
                                <div class="text-sm text-gray-500">Visa, Mastercard, JCB</div>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-down toggle-icon transition-transform"></i>
                    </button>

                    <div id="card" class="payment-method-content hidden border-t px-6 py-6">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="border rounded-xl p-6 flex items-center justify-center cursor-pointer hover:bg-gray-50 card-option" data-method="visa">
                                <span class="font-bold">VISA</span>
                            </div>
                            <div class="border rounded-xl p-6 flex items-center justify-center cursor-pointer hover:bg-gray-50 card-option" data-method="mastercard">
                                <span class="font-bold">Mastercard</span>
                            </div>
                            <div class="border rounded-xl p-6 flex items-center justify-center cursor-pointer hover:bg-gray-50 card-option" data-method="jcb">
                                <span class="font-bold">JCB</span>
                            </div>
                        </div>
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
                <span id="subtotalAmount">Rp{{ number_format($order->total_amount) }}</span>
            </div>

            <div class="flex justify-between text-gray-600 text-sm mb-2">
                <span>Local Tax</span>
                <span id="taxAmount">Rp{{ number_format($order->total_amount * 0.20) }}</span>
            </div>

            <div class="flex justify-between text-gray-600 text-sm mb-4">
                <span>Service Fee</span>
                <span id="serviceFeeAmount">Rp{{ number_format($order->total_amount * 0.05) }}</span>
            </div>

            <hr class="my-4">

            <div class="flex justify-between font-semibold text-gray-900 text-lg mb-4">
                <span>Grand Total</span>
                <span id="grandTotalAmount">Rp{{ number_format($order->total_amount * 1.25) }}</span>
            </div>

            <hr class="my-4">

            <!-- Add Voucher Button -->
            <button id="openVoucherModal" type="button" class="w-full mb-4 py-3 border-2 border-indigo-600 text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 flex items-center justify-center gap-2">
                <i class="fa-solid fa-tag"></i>
                Add Voucher
            </button>

            <!-- Voucher Modal -->
            <div id="voucherModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4" aria-hidden="true">
                <div class="fixed inset-0 bg-black/40 transition-opacity" data-voucher-backdrop></div>

                <div class="bg-white rounded-2xl shadow-lg max-w-2xl w-full mx-auto z-10 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-ticket text-indigo-600 text-2xl"></i>
                                <h2 class="text-2xl font-semibold">Add Voucher</h2>
                            </div>
                            <button id="closeVoucherModal" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
                        </div>

                        <div>
                            <label class="font-medium text-gray-700">Enter Voucher Code</label>
                            <input type="text" id="voucherCodeInput" placeholder="Masukkan kode voucher"
                                class="w-full mt-3 px-4 py-3 border-2 border-indigo-600 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button id="cancelVoucherBtn" class="px-6 py-2 text-gray-700 font-semibold hover:bg-gray-100 rounded-lg">Cancel</button>
                            <button id="applyVoucherBtn" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700">Apply</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="mb-3">
                    <label class="inline-flex items-center">
                        <input id="agreeTerms" type="checkbox" class="mr-2 w-4 h-4 accent-indigo-600">
                        <span class="text-sm text-gray-600">I agree <a href="#" class="text-indigo-600 font-semibold">Terms & Conditions</a> that applies in Artatix</span>
                    </label>
                </div>

                <div class="mb-3">
                    <label class="inline-flex items-center">
                        <input id="agreePrivacy" type="checkbox" class="mr-2 w-4 h-4 accent-indigo-600">
                        <span class="text-sm text-gray-600">I agree <a href="#" class="text-indigo-600 font-semibold">Privacy Policy & Data Processing</a> that applies in Artatix</span>
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
    const payBtn = document.getElementById('payNowBtn');
    const agreeTerms = document.getElementById('agreeTerms');
    const agreePrivacy = document.getElementById('agreePrivacy');
    const form = document.getElementById('paymentSelectionForm');

    // Voucher modal elements
    const openVoucherBtn = document.getElementById('openVoucherModal');
    const voucherModal = document.getElementById('voucherModal');
    const closeVoucherBtn = document.getElementById('closeVoucherModal');
    const voucherBackdrop = voucherModal.querySelector('[data-voucher-backdrop]');
    const voucherCodeInput = document.getElementById('voucherCodeInput');
    const applyVoucherBtn = document.getElementById('applyVoucherBtn');
    const cancelVoucherBtn = document.getElementById('cancelVoucherBtn');

    let selectedMethod = null;

    // Payment method toggle functionality
    const toggleButtons = document.querySelectorAll('.payment-method-toggle');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('data-target');
            const content = document.getElementById(target);
            const icon = this.querySelector('.toggle-icon');
            
            content.classList.toggle('hidden');
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });

    // Bank option selection
    const bankOptions = document.querySelectorAll('.bank-option');
    bankOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectPaymentMethod(this.getAttribute('data-method'));
        });
    });

    // Wallet option selection
    const walletOptions = document.querySelectorAll('.wallet-option');
    walletOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectPaymentMethod(this.getAttribute('data-method'));
        });
    });

    // Card option selection
    const cardOptions = document.querySelectorAll('.card-option');
    cardOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectPaymentMethod(this.getAttribute('data-method'));
        });
    });

    function selectPaymentMethod(method) {
        selectedMethod = method;
        updatePayState();
        // Optional: show visual feedback
        console.log('Selected method:', method);
    }

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

    agreeTerms.addEventListener('change', updatePayState);
    agreePrivacy.addEventListener('change', updatePayState);

    // Voucher modal handlers
    function showVoucherModal() {
        voucherModal.classList.remove('hidden');
        voucherModal.classList.add('flex');
        voucherCodeInput.focus();
        document.body.style.overflow = 'hidden';
    }

    function hideVoucherModal() {
        voucherModal.classList.add('hidden');
        voucherModal.classList.remove('flex');
        document.body.style.overflow = '';
        voucherCodeInput.value = '';
    }

    openVoucherBtn.addEventListener('click', showVoucherModal);
    closeVoucherBtn.addEventListener('click', hideVoucherModal);
    voucherBackdrop.addEventListener('click', hideVoucherModal);
    cancelVoucherBtn.addEventListener('click', hideVoucherModal);

    // Close voucher modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !voucherModal.classList.contains('hidden')) {
            hideVoucherModal();
        }
    });

    applyVoucherBtn.addEventListener('click', function() {
        const code = voucherCodeInput.value.trim();
        if (!code) {
            alert('Please enter a voucher code');
            return;
        }
        // TODO: Validate voucher code with backend
        alert('Voucher "' + code + '" applied (stub)');
        hideVoucherModal();
    });

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

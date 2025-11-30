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
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option transition-all" data-method="bca">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbca.png&w=320&q=50" alt="BCA" class="h-12">
                            </div>
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option transition-all" data-method="bni">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbni.png&w=320&q=50" alt="BNI" class="h-12">
                            </div>
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option transition-all" data-method="bri">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbri.png&w=320&q=50" alt="BRI" class="h-12">
                            </div>
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option transition-all" data-method="mandiri">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fmandiri.png&w=320&q=50" alt="Mandiri" class="h-12">
                            </div>
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option transition-all" data-method="bsi">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fbsi.png&w=320&q=50" alt="BSI" class="h-12">
                            </div>
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 bank-option transition-all" data-method="CimbNiaga">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fcimb.png&w=320&q=50" alt="CimbNiaga" class="h-12">
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
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option transition-all" data-method="ovo">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fovo.png&w=320&q=50" alt="OVO" class="h-12">
                            </div>
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option transition-all" data-method="dana">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fdana.png&w=320&q=50" alt="Dana" class="h-12">
                            </div>
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option transition-all" data-method="qris">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fqris.png&w=320&q=50" alt="Qris" class="h-12">
                            </div>
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option transition-all" data-method="gopay">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fgopay.png&w=320&q=50" alt="Gopay" class="h-12">
                            </div>
                            <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 wallet-option transition-all" data-method="shopeepay">
                                <img src="https://artatix.co.id/_next/image?url=https%3A%2F%2Fassets.artatix.co.id%2Fpayment%2Fshopeepay.png&w=320&q=50" alt="Shopeepay" class="h-12">
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

            <!-- Voucher applied notification (hidden by default) -->
            <div id="voucherNotification" class="hidden items-center gap-2 bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded mb-4 text-sm">
                <i class="fa-solid fa-check-circle"></i>
                <span id="voucherNotificationText">Voucher applied</span>
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

            <div id="discountRow" class="flex justify-between text-green-600 text-sm mb-2" @if(!$order->voucher_id)style="display:none" @endif>
                <span>Discount (Voucher)</span>
                <span id="discountAmount">-Rp{{ number_format($order->discount_amount ?? 0) }}</span>
            </div>

            <hr class="my-4">

            <div class="flex justify-between font-semibold text-gray-900 text-lg mb-4">
                <span>Total</span>
                <span id="grandTotalAmount">Rp{{ number_format($order->total_amount - ($order->discount_amount ?? 0)) }}</span>
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
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-building-columns text-indigo-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 capitalize" id="selectedMethodName">-</p>
                                    <p class="text-xs text-gray-500">Secure Payment</p>
                                </div>
                            </div>
                        </div>

                        <!-- QRIS Code Display (shown only for QRIS) -->
                        <div id="qrisCodeSection" class="hidden text-center border rounded-xl p-6 bg-white">
                            <p class="text-sm text-gray-600 mb-4 font-semibold">Scan this QRIS code with your e-wallet</p>
                            <img src="{{ asset('images/qris/qris.svg') }}" alt="QRIS Code" class="w-48 h-48 mx-auto border rounded-lg shadow-sm">
                            <p class="text-xs text-gray-500 mt-4">Use any e-wallet app to scan and complete payment</p>
                        </div>

                        <!-- Amount Display -->
                        <div class="border-t border-b py-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium" id="modalSubtotal">Rp0</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Tax & Fee</span>
                                <span class="font-medium" id="modalTaxFee">Rp0</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-xl text-indigo-600" id="modalTotal">Rp0</span>
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
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-check text-3xl text-green-600"></i>
                                </div>
                            </div>
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
                            <button id="completePaymentBtn" type="button" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">Continue to Confirmation</button>
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

    // Prepare order items for client-side cart sync
    const orderItems = @json($order->items->map(function($it) { return ['ticket_type_id' => $it->ticket_type_id, 'quantity' => $it->quantity]; }));
    const cartAddUrl = '{{ route('cart.add') }}';

    // Voucher modal elements
    const openVoucherBtn = document.getElementById('openVoucherModal');
    const voucherModal = document.getElementById('voucherModal');
    const closeVoucherBtn = document.getElementById('closeVoucherModal');
    const voucherBackdrop = voucherModal.querySelector('[data-voucher-backdrop]');
    const voucherCodeInput = document.getElementById('voucherCodeInput');
    const applyVoucherBtn = document.getElementById('applyVoucherBtn');
    const cancelVoucherBtn = document.getElementById('cancelVoucherBtn');

    let selectedMethod = null;
    let methodMap = {
        'bca': 'BCA',
        'bjb': 'Bank BJB',
        'bni': 'BNI',
        'bri': 'BANK BRI',
        'bsi': 'BSI',
        'sampoerna': 'Bank Sampoerna',
        'cimb': 'CIMB NIAGA',
        'mandiri': 'Mandiri',
        'permata': 'Permata Bank',
        'ovo': 'OVO',
        'dana': 'DANA',
        'qris': 'QRIS',
        'gopay': 'Gopay',
        'linkaja': 'Link Aja!',
        'astrapay': 'AstraPay',
        'jeniuspay': 'Jenius Pay',
        'shopeepay': 'ShopeePay',
        'visa': 'VISA',
        'mastercard': 'Mastercard',
        'jcb': 'JCB'
    };

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
            selectPaymentMethod(this.getAttribute('data-method'), this);
        });
    });

    // Wallet option selection
    const walletOptions = document.querySelectorAll('.wallet-option');
    walletOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectPaymentMethod(this.getAttribute('data-method'), this);
        });
    });

    // Card option selection
    const cardOptions = document.querySelectorAll('.card-option');
    cardOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectPaymentMethod(this.getAttribute('data-method'), this);
        });
    });

    function selectPaymentMethod(method, element) {
        // Remove previous selection
        document.querySelectorAll('.bank-option, .wallet-option, .card-option').forEach(opt => {
            opt.classList.remove('border-indigo-600', 'bg-indigo-50');
            opt.classList.add('border-gray-300');
        });

        // Highlight selected
        element.classList.remove('border-gray-300');
        element.classList.add('border-indigo-600', 'bg-indigo-50', 'border-2');

        selectedMethod = method;
        updatePayState();
        console.log('Selected method:', method);
    }

    function updatePayState() {
        if (selectedMethod && agreeTerms.checked && agreePrivacy.checked) {
            payBtn.disabled = false;
            payBtn.classList.remove('bg-indigo-200');
            payBtn.classList.add('bg-indigo-600', 'cursor-pointer');
        } else {
            payBtn.disabled = true;
            payBtn.classList.remove('bg-indigo-600', 'cursor-pointer');
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

    applyVoucherBtn.addEventListener('click', async function() {
        const code = voucherCodeInput.value.trim();
        if (!code) {
            alert('Please enter a voucher code');
            return;
        }

        applyVoucherBtn.disabled = true;
        applyVoucherBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin mr-2"></i>Applying...';

        try {
            const response = await fetch('{{ route('purchase.applyVoucher', $order->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ code: code })
            });

            const data = await response.json();

            if (data.success) {
                // Update discount display (make the discount row visible in the order card)
                const discountRow = document.getElementById('discountRow');
                const discountAmountEl = document.getElementById('discountAmount');
                discountRow.style.display = 'flex';
                discountAmountEl.textContent = '-Rp' + Number(data.discount_amount).toLocaleString('id-ID');

                // Update grand total
                const subtotal = parseFloat(document.getElementById('subtotalAmount').textContent.replace(/[^\d]/g, ''));
                const newTotal = subtotal - data.discount_amount;
                document.getElementById('grandTotalAmount').textContent = 'Rp' + Number(newTotal).toLocaleString('id-ID');

                // Show success notification in the order summary card
                const voucherNotify = document.getElementById('voucherNotification');
                const voucherNotifyText = document.getElementById('voucherNotificationText');
                voucherNotifyText.textContent = 'Voucher "' + code.toUpperCase() + '" applied — -Rp' + Number(data.discount_amount).toLocaleString('id-ID');
                voucherNotify.classList.remove('hidden');
                voucherNotify.classList.add('flex');

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    try {
                        voucherNotify.classList.remove('flex');
                        voucherNotify.classList.add('hidden');
                    } catch (e) {}
                }, 5000);

                hideVoucherModal();
            } else {
                alert('❌ ' + (data.message || 'Failed to apply voucher'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error applying voucher. Please try again.');
        } finally {
            applyVoucherBtn.disabled = false;
            applyVoucherBtn.textContent = 'Apply';
        }
    });

    // Midtrans modal handlers
    function showMidtransModal() {
        midtransModal.classList.remove('hidden');
        midtransModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Display selected method name
        const methodName = methodMap[selectedMethod] || selectedMethod.toUpperCase();
        document.getElementById('selectedMethodName').textContent = methodName;

        // Show/hide QRIS code section
        const qrisCodeSection = document.getElementById('qrisCodeSection');
        if (selectedMethod === 'qris') {
            qrisCodeSection.classList.remove('hidden');
        } else {
            qrisCodeSection.classList.add('hidden');
        }

        // Display amounts
        const subtotal = parseFloat(document.getElementById('subtotalAmount').textContent.replace(/[^\d]/g, ''));
        const discountText = document.getElementById('discountAmount')?.textContent || '';
        const discount = discountText ? parseFloat(discountText.replace(/[^\d]/g, '')) : 0;
        const total = subtotal - discount;

        document.getElementById('modalSubtotal').textContent = 'Rp' + Number(subtotal).toLocaleString('id-ID');
        
        // Hide tax/fee row or show discount if applicable
        const taxFeeRow = document.querySelector('[id="modalTaxFee"]').parentElement;
        if (discount > 0) {
            document.getElementById('modalTaxFee').textContent = '-Rp' + Number(discount).toLocaleString('id-ID');
            taxFeeRow.querySelector('span:first-child').textContent = 'Discount';
        } else {
            taxFeeRow.style.display = 'none';
        }
        
        document.getElementById('modalTotal').textContent = 'Rp' + Number(total).toLocaleString('id-ID');

        // Reset progress bar and states
        document.getElementById('progressBar').style.width = '0%';
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
        // Simulate payment processing with progress bar
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                completePaymentProcessing();
            }
            document.getElementById('progressBar').style.width = progress + '%';
        }, 300);
    }

    function completePaymentProcessing() {
        // Show success state after 2 seconds
        setTimeout(() => {
            processingState.classList.add('hidden');
            successState.classList.remove('hidden');
            processingButtons.classList.add('hidden');
            successButtons.classList.remove('hidden');

            // Generate fake transaction ID
            const transactionId = 'TXN' + Date.now();
            document.getElementById('transactionId').textContent = 'Transaction ID: ' + transactionId;
        }, 1000);
    }

    cancelPaymentBtn.addEventListener('click', hideMidtransModal);

    completePaymentBtn.addEventListener('click', function() {
        // Create hidden input for payment method and submit form
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'payment_method';
        input.value = selectedMethod;
        form.appendChild(input);
        form.submit();
    });

    payBtn.addEventListener('click', async function () {
        if (!selectedMethod) return alert('Pilih metode pembayaran dulu.');

        // Use a hidden iframe POST to ensure server receives cart.add and updates session
        const syncCartViaIframe = () => new Promise((resolve) => {
            try {
                const iframeName = 'cart_sync_iframe_' + Date.now();
                const iframe = document.createElement('iframe');
                iframe.name = iframeName;
                iframe.style.display = 'none';
                document.body.appendChild(iframe);

                const syncForm = document.createElement('form');
                syncForm.method = 'POST';
                syncForm.action = cartAddUrl;
                syncForm.target = iframeName;

                // CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = token || '';
                syncForm.appendChild(csrfInput);

                // concert_id
                const concertInput = document.createElement('input');
                concertInput.type = 'hidden';
                concertInput.name = 'concert_id';
                concertInput.value = '{{ $order->concert_id }}';
                syncForm.appendChild(concertInput);

                // ticket_type_id[] and quantity[]
                orderItems.forEach(it => {
                    const t = document.createElement('input');
                    t.type = 'hidden';
                    t.name = 'ticket_type_id[]';
                    t.value = it.ticket_type_id;
                    syncForm.appendChild(t);

                    const q = document.createElement('input');
                    q.type = 'hidden';
                    q.name = 'quantity[]';
                    q.value = it.quantity;
                    syncForm.appendChild(q);
                });

                document.body.appendChild(syncForm);

                // Resolve when iframe loads (server processed request)
                iframe.addEventListener('load', function onLoad() {
                    iframe.removeEventListener('load', onLoad);
                    // cleanup
                    setTimeout(() => {
                        try { document.body.removeChild(iframe); } catch (e) {}
                        try { document.body.removeChild(syncForm); } catch (e) {}
                    }, 200);
                    resolve(true);
                });

                // Submit the sync form
                syncForm.submit();

                // Fallback: resolve after 2s in case load doesn't fire
                setTimeout(() => resolve(true), 2000);
            } catch (e) {
                console.error('iframe sync failed', e);
                resolve(false);
            }
        });

        await syncCartViaIframe();

        // create a hidden input and submit payment form
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

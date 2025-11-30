@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }
</style>

<div class="w-full bg-gray-50 py-10 font-poppins">

    {{-- 🔥 TOMBOL LIHAT DETAIL PESANAN (PAKAI existingOrder, BUKAN SESSION) --}}
    @if($existingOrder)
    <div class="max-w-6xl mx-auto px-6 mb-4 flex items-center gap-3">
        <a href="{{ route('purchase.detail', $existingOrder->id) }}"
            class="text-indigo-600 underline font-semibold">
            Lihat Detail Pesanan Anda →
        </a>
    </div>
    @endif

    {{-- STEP INDICATOR --}}
    <div class="max-w-6xl mx-auto px-6 mb-10">
        <div class="flex items-center space-x-3 text-gray-500 text-sm">
            <div class="flex items-center space-x-2 text-indigo-600 font-semibold">
                <div class="w-7 h-7 flex items-center justify-center bg-indigo-100 rounded-full">1</div>
                <span>Pilih Kategori</span>
            </div>
            <span>›</span> <span>2. Detail Pesanan</span>
            <span>›</span> <span>3. Metode Pembayaran</span>
            <span>›</span> <span>4. Pembayaran</span>
        </div>
    </div>

    {{-- FLASH / ERROR MESSAGES --}}
    <div class="max-w-6xl mx-auto px-6 mb-4">
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-6">

        {{-- LEFT --}}
        <div class="md:col-span-2 space-y-8">

            {{-- BANNER --}}
            @php
            $poster = $concert->image_url
            ? asset($concert->image_url)
            : asset('images/default-concert.jpg');
            @endphp

            <div class="lg:col-span-1">
                <div class="rounded-lg overflow-hidden shadow-md sticky top-8">
                    <img src="{{ $poster }}"
                        alt="{{ $concert->title }}"
                        class="w-full h-96 object-cover bg-gray-200">
                </div>
            </div>


            {{-- CARD KATEGORI --}}
            <div class="bg-white shadow-xl border border-gray-200 rounded-3xl p-8">

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fa-solid fa-ticket text-indigo-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Kategori Tiket</h2>
                </div>

                <div class="space-y-6">
                    @foreach($ticketTypes as $t)
                    @php $soldOut = $t->sold >= $t->quota; @endphp

                        <div class="relative bg-white border border-gray-200 shadow-md rounded-2xl px-6 py-5">

                            {{-- TICKET CUT --}}
                            <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-7 h-7 bg-white rounded-full border border-gray-200"></div>
                            <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-7 h-7 bg-white rounded-full border border-gray-200"></div>

                            <div class="flex items-center justify-between">

                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            {{ strtoupper($t->name) }}
                                        </h3>

                                        @if(!$soldOut)
                                            <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-full">On Sale</span>
                                        @else
                                            <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">Sold Out</span>
                                        @endif
                                    </div>

                                    <p class="text-gray-700 text-base font-medium">
                                        Rp{{ number_format($t->price) }}
                                    </p>
                                </div>

                                <div>
                                    @if(!$soldOut)
                                        <div class="flex items-center gap-3">
                                            <button type="button" class="qty-minus w-10 h-10 rounded-full bg-indigo-600 text-white font-bold hover:bg-indigo-700 flex items-center justify-center" data-id="{{ $t->id }}">−</button>
                                            <input type="text" class="qty-display w-12 text-center text-lg font-bold bg-transparent" value="0" readonly data-id="{{ $t->id }}" data-price="{{ $t->price }}">
                                            <button type="button" class="qty-plus w-10 h-10 rounded-full bg-indigo-600 text-white font-bold hover:bg-indigo-700 flex items-center justify-center" data-id="{{ $t->id }}">+</button>
                                        </div>
                                    @else
                                        <button class="px-6 py-2 bg-gray-300 text-gray-600 rounded-lg font-medium cursor-not-allowed">
                                            Sold Out
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT: TOTAL --}}
        <div class="bg-white shadow-lg rounded-3xl p-6 border h-fit">

            <div class="flex items-center gap-2 mb-5">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-600 flex items-center justify-center rounded-lg">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Rincian Pesanan</h3>
            </div>

            <div id="orderDetailContainer" class="hidden">

                <div id="ticketItemsList" class="space-y-3 mb-3">
                    {{-- Items will be inserted here --}}
                </div>

                <hr class="my-4">

                <div class="flex justify-between text-gray-600 text-sm mb-2">
                    <span>Subtotal</span>
                    <span id="subtotalValue">Rp0</span>
                </div>

                <div class="flex justify-between font-semibold text-gray-900 text-lg mb-4">
                    <span id="totalLabel">Total</span>
                    <span id="totalValue">Rp0</span>
                </div>

                <hr class="my-4">
            </div>

            <form id="paymentForm" action="{{ route('purchase.store', $concert->id) }}"
                method="POST">
                @csrf

                <button id="bayarBtn" type="submit"
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold disabled:bg-gray-300 disabled:cursor-not-allowed transition"
                    disabled>
                    Beli Sekarang
                </button>
            </form>

        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const bayarBtn = document.getElementById("bayarBtn");
        const form = document.getElementById("paymentForm");
        const detailBox = document.getElementById("orderDetailContainer");
        const ticketItemsList = document.getElementById("ticketItemsList");
        const subtotalValue = document.getElementById("subtotalValue");
        const totalValue = document.getElementById("totalValue");
        const totalLabel = document.getElementById("totalLabel");

        // Store quantities in an object instead of relying on DOM
        const quantities = {};

        // Handle tombol +
        document.querySelectorAll('.qty-plus').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const display = document.querySelector(`.qty-display[data-id="${id}"]`);
                let qty = parseInt(display.value) || 0;
                if (qty < 5) {
                    qty++;
                    display.value = qty;
                    quantities[id] = qty;
                    updateAllQty();
                } else {
                    alert('Maksimal 5 tiket per kategori');
                }
            });
        });

        // Handle tombol -
        document.querySelectorAll('.qty-minus').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const display = document.querySelector(`.qty-display[data-id="${id}"]`);
                let qty = parseInt(display.value) || 0;
                if (qty > 0) {
                    qty--;
                    display.value = qty;
                    quantities[id] = qty;
                    updateAllQty();
                }
            });
        });

        function updateAllQty() {
            let totalQty = 0;
            let totalPrice = 0;
            const selectedItems = [];
            const ticketTypesMap = {};

            // Build ticket types map
            document.querySelectorAll('.qty-display').forEach(display => {
                const id = display.dataset.id;
                const price = parseInt(display.dataset.price) || 0;
                const name = display.closest('.relative').querySelector('h3').innerText;
                ticketTypesMap[id] = {
                    name,
                    price
                };
            });

            // Calculate from quantities object
            Object.keys(quantities).forEach(id => {
                const qty = quantities[id];
                if (qty > 0) {
                    totalQty += qty;
                    const ticketType = ticketTypesMap[id];
                    if (ticketType) {
                        totalPrice += ticketType.price * qty;
                        selectedItems.push({
                            id,
                            name: ticketType.name,
                            qty,
                            price: ticketType.price
                        });
                    }
                }
            });

            if (totalQty > 0) {
                // Remove old hidden inputs
                form.querySelectorAll('input[name^="ticket_type_id"], input[name^="quantity"]').forEach(el => {
                    el.remove();
                });

                // Add inputs for each selected ticket from quantities object
                selectedItems.forEach(item => {
                    const input1 = document.createElement('input');
                    input1.type = 'hidden';
                    input1.name = 'ticket_type_id[]';
                    input1.value = item.id;
                    form.appendChild(input1);

                    const input2 = document.createElement('input');
                    input2.type = 'hidden';
                    input2.name = 'quantity[]';
                    input2.value = item.qty;
                    form.appendChild(input2);

                    console.log('Added to form:', {
                        ticket_type_id: item.id,
                        quantity: item.qty
                    });
                });

                // Update display with all items
                ticketItemsList.innerHTML = '';
                selectedItems.forEach(item => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'mb-3';
                    itemDiv.innerHTML = `
                        <div class="flex justify-between font-medium text-gray-900">
                            <span>${item.name}</span>
                            <span>Rp${(item.price * item.qty).toLocaleString('id-ID')}</span>
                        </div>
                        <p class="text-sm text-gray-500">x${item.qty}</p>
                    `;
                    ticketItemsList.appendChild(itemDiv);
                });

                detailBox.classList.remove("hidden");
                subtotalValue.textContent = "Rp" + totalPrice.toLocaleString('id-ID');
                totalValue.textContent = "Rp" + totalPrice.toLocaleString('id-ID');
                totalLabel.textContent = "Total " + totalQty + " Tiket";
                bayarBtn.disabled = false;
            } else {
                // Remove hidden inputs when no qty selected
                form.querySelectorAll('input[name^="ticket_type_id"], input[name^="quantity"]').forEach(el => {
                    el.remove();
                });
                detailBox.classList.add("hidden");
                bayarBtn.disabled = true;
            }
        }

        // Use native form submit; validate before submitting
        form.addEventListener('submit', function(e) {
            let totalQty = 0;
            Object.keys(quantities).forEach(id => {
                totalQty += quantities[id] || 0;
            });

            if (totalQty === 0) {
                e.preventDefault();
                alert('Pilih jumlah tiket terlebih dahulu');
                return;
            }

            // DEBUG: Log form data
            console.log('Form Data Before Submit:');
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // Allow native submit (browser will follow redirects or auth)
        });
    });
</script>

@endsection
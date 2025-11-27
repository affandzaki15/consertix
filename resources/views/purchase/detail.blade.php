@extends('layouts.app')

@section('content')

<div class="w-full bg-gray-50 py-10 font-poppins">

    {{-- STEP INDICATOR --}}
    <div class="max-w-6xl mx-auto px-6 mb-10">
        <div class="flex items-center space-x-3 text-gray-500 text-sm">
            <span class="flex items-center gap-2">
                <div class="w-7 h-7 bg-indigo-600 text-white flex items-center justify-center rounded-full">1</div>
                Pilih Kategori
            </span>
            <span>›</span>

            <span class="flex items-center gap-2 text-indigo-600 font-semibold">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-600 flex items-center justify-center rounded-full">2</div>
                Detail Pesanan
            </span>

            <span>›</span> <span>3. Metode Pembayaran</span>
            <span>›</span> <span>4. Pembayaran</span>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-6">

        {{-- LEFT CONTENT --}}
        <div class="md:col-span-2 space-y-6">

            {{-- EVENT INFORMATION --}}
            <div class="">
                <h1 class="text-3xl font-bold text-gray-900">{{ $concert->title }}</h1>
                <p class="text-gray-600 mt-1">
                    {{ \Carbon\Carbon::parse($concert->date)->format('d F Y') }}
                    • {{ $concert->time ?? '19:00 – 22:00' }}
                </p>
                <p class="text-gray-600">
                    {{ $concert->location }}
                </p>
            </div>

            {{-- CARD DATA PEMESAN --}}
            <div class="bg-white rounded-3xl shadow-md border p-8">

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Data Pemesan</h2>
                </div>

                {{-- NAMA --}}
                <div class="mb-5">
                    <label class="font-medium text-gray-700">Nama Lengkap</label>
                    <div class="w-full mt-2 p-3 border rounded-xl bg-gray-50">
                        {{ auth()->user()->name }}
                    </div>
                </div>

                {{-- EMAIL --}}
                <div class="mb-5">
                    <label class="font-medium text-gray-700">Email</label>
                    <div class="w-full mt-2 p-3 border rounded-xl bg-gray-50">
                        {{ auth()->user()->email }}
                    </div>
                </div>

                {{-- NOMOR WHATSAPP --}}
                <div class="mb-5">
                    <label class="font-medium text-gray-700">No. WhatsApp</label>
                    <div class="w-full mt-2 p-3 border rounded-xl bg-gray-50">
                        {{ auth()->user()->phone ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

        {{-- RIGHT SIDE: ORDER SUMMARY --}}
        <div class="bg-white shadow-lg rounded-3xl p-6 border h-fit">

            <div class="flex items-center gap-2 mb-5">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-600 flex items-center justify-center rounded-lg">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Rincian Pesanan</h3>
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
                <span>Total Bayar</span>
                <span>Rp{{ number_format($order->total_amount) }}</span>
            </div>

            <hr class="my-4">

            <div class="flex justify-between items-center">
                <a href="{{ route('purchase.show', $concert->id) }}"
                    class="w-14 h-12 border rounded-xl flex items-center justify-center text-gray-600 hover:bg-gray-100">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                <form id="orderDetailForm" action="{{ route('purchase.processDetail', $order->id) }}" method="POST" class="flex-1 ml-3">
                    @csrf
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                    <input type="hidden" name="phone" value="{{ auth()->user()->phone ?? '' }}">
                    <button id="openConfirmModal" type="button"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold">
                        Lanjutkan
                    </button>
                </form>

                <!-- Confirmation Modal -->
                <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4" aria-hidden="true">
                    <div class="fixed inset-0 bg-black/40 transition-opacity" data-modal-backdrop></div>

                    <div class="bg-white rounded-2xl shadow-lg max-w-2xl w-full mx-auto z-10 overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h2 class="text-2xl font-semibold">Konfirmasi Pesanan</h2>
                                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">×</button>
                            </div>

                            <p class="text-gray-600 mt-4">Pastikan data sebelum melanjutkan</p>

                            <div class="mt-6 grid grid-cols-1 gap-3 text-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center"> 
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm text-gray-500">-</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center"> 
                                        <i class="fa-regular fa-envelope"></i>
                                    </div>
                                    <div class="flex-1 text-sm text-indigo-600">
                                        {{ auth()->user()->email }}
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center"> 
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <div class="flex-1 text-sm text-indigo-600">
                                        {{ auth()->user()->phone ?? '-'}}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 text-gray-800">
                                <p class="font-semibold">Informasi Penting :</p>
                                <ol class="list-decimal list-inside mt-2 text-sm text-gray-600 space-y-2">
                                    <li>Invoice dan E-Tiket akan dikirim ke email berikut <span class="text-indigo-600">{{ auth()->user()->email }}</span></li>
                                    <li>E-Tiket juga akan dikirim melalui WhatsApp ke nomor berikut <span class="text-indigo-600">{{ auth()->user()->phone ?? '-' }}</span></li>
                                    <li>Jika belum menerima notifikasi email setelah pembayaran:
                                        <ul class="list-disc list-inside ml-4 mt-2 text-sm text-gray-600">
                                            <li>Cari “Artatix” pada kolom pencarian email</li>
                                            <li>Periksa folder spam/promosi pada email</li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <a href="{{ route('profile.edit') }}" class="px-4 py-2 rounded-xl border bg-white text-gray-700">Edit Data</a>
                                <button id="confirmContinueBtn" class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Saya Mengerti</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const openBtn = document.getElementById('openConfirmModal');
    const modal = document.getElementById('confirmModal');
    const closeBtn = document.getElementById('closeModalBtn');
    const backdrop = modal ? modal.querySelector('[data-modal-backdrop]') : null;
    const confirmBtn = document.getElementById('confirmContinueBtn');
    const form = document.getElementById('orderDetailForm');

    if (!modal || !openBtn || !confirmBtn || !form) return;

    function showModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // focus the confirm button for keyboard users
        confirmBtn.focus();
        document.body.style.overflow = 'hidden';
    }

    function hideModal() {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        openBtn.focus();
    }

    openBtn.addEventListener('click', function (e) {
        e.preventDefault();
        showModal();
    });

    closeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        hideModal();
    });

    if (backdrop) {
        backdrop.addEventListener('click', function () { hideModal(); });
    }

    // close on ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            hideModal();
        }
    });

    // when user confirms, submit the form
    confirmBtn.addEventListener('click', function (e) {
        // optionally disable button to avoid double submits
        confirmBtn.disabled = true;
        form.submit();
    });
});
</script>

@endsection

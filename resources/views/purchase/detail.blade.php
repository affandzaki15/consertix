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

                <form id="orderDataForm" class="space-y-5">
                    {{-- NAMA LENGKAP --}}
                    <div>
                        <label class="font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="fullName" placeholder="Masukkan nama lengkap" 
                            class="w-full mt-2 px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            value="{{ auth()->user()->name }}" required>
                    </div>

                    {{-- IDENTITY TYPE & NUMBER --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-medium text-gray-700">Jenis Identitas <span class="text-red-500">*</span></label>
                            <select id="identityType" class="w-full mt-2 px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                <option value="">Pilih jenis identitas</option>
                                <option value="ktp">KTP</option>
                                <option value="passport">Passport</option>
                                <option value="sim">SIM</option>
                                <option value="kartu_pelajar">Kartu Pelajar</option>
                            </select>
                        </div>
                        <div>
                            <label class="font-medium text-gray-700">Nomor Identitas <span class="text-red-500">*</span></label>
                            <input type="text" id="identityNumber" placeholder="Minimal 5 karakter"
                                class="w-full mt-2 px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500" required minlength="5" pattern="[0-9]{5,}">
                            <p id="identityNumberError" class="mt-1 text-sm text-red-500 hidden">Nomor identitas minimal 5 karakter</p>
                        </div>
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" placeholder="Masukkan email"
                            class="w-full mt-2 px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            value="{{ auth()->user()->email }}" required>
                    </div>

                    {{-- WHATSAPP NUMBER --}}
                    <div>
                        <label class="font-medium text-gray-700">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex items-center gap-2 px-3 py-3 border border-gray-200 rounded-xl bg-gray-50">
                                <img src="https://flagcdn.com/w40/id.png" alt="Indonesia" class="w-6 h-4">
                                <span class="text-gray-700">+62</span>
                            </div>
                            <input type="text" id="whatsappNumber" placeholder="8123456789"
                                class="flex-1 px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value="{{ ltrim(auth()->user()->phone ?? '', '+62') }}" required>
                        </div>
                    </div>
                </form>

            </div>

        </div>

        {{-- RIGHT SIDE: ORDER SUMMARY --}}
        <div class="bg-white shadow-lg rounded-3xl p-6 border h-fit">

            {{-- Event Icon & Title --}}
            <div class="mb-6 pb-6 border-b border-gray-200">
                {{-- <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-music text-indigo-600 text-lg"></i>
                </div> --}}
                <h3 class="text-lg font-bold text-gray-900">Rincian Pesanan</h3>
            </div>

            {{-- Ticket Items --}}
            @foreach($order->items as $item)
                <div class="mb-4 pb-4 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-semibold text-gray-900">{{ $item->ticketType->name }}</span>
                        <span class="font-bold text-gray-900">Rp{{ number_format($item->price) }}</span>
                    </div>
                    <p class="text-sm text-gray-500">x{{ $item->quantity }}</p>
                </div>
            @endforeach

            {{-- Pricing Summary --}}
            <div class="mt-6 pt-4 space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($order->total_amount) }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg text-gray-900 pt-2 border-t border-gray-200">
                    <span>Total Bayar</span>
                    <span>Rp{{ number_format($order->total_amount) }}</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex gap-3">
                <a href="{{ route('purchase.show', $concert->id) }}"
                    class="w-12 h-12 border border-gray-300 rounded-xl flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                <form id="orderDetailForm" action="{{ route('purchase.processDetail', $order->id) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" id="hiddenName" name="name" value="">
                    <input type="hidden" id="hiddenEmail" name="email" value="">
                    <input type="hidden" id="hiddenPhone" name="phone" value="">
                    <input type="hidden" id="hiddenIdentityType" name="identity_type" value="">
                    <input type="hidden" id="hiddenIdentityNumber" name="identity_number" value="">
                    <button id="openConfirmModal" type="button"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition-colors">
                        Lanjutkan
                    </button>
                </form>
            </div>

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
                                        <i class="fa-solid fa-user text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500">Nama</p>
                                        <p class="text-sm font-medium text-gray-900" id="modalName">-</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center"> 
                                        <i class="fa-solid fa-envelope text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500">Email</p>
                                        <p class="text-sm font-medium text-gray-900" id="modalEmail">-</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center"> 
                                        <i class="fa-solid fa-phone text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500">WhatsApp</p>
                                        <p class="text-sm font-medium text-gray-900" id="modalPhone">-</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 text-gray-800">
                                <p class="font-semibold">Informasi Penting :</p>
                                <ol class="list-decimal list-inside mt-2 text-sm text-gray-600 space-y-2">
                                    <li>Invoice dan E-Tiket akan dikirim ke email anda</li>
                                    <li>E-Tiket juga akan dikirim melalui WhatsApp ke nomor anda</li>
                                    <li>Jika belum menerima notifikasi email setelah pembayaran:
                                        <ul class="list-disc list-inside ml-4 mt-2 text-sm text-gray-600">
                                            <li>Cari “Artatix” pada kolom pencarian email</li>
                                            <li>Periksa folder spam/promosi pada email</li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button id="closeModalBtn2" class="px-4 py-2 rounded-xl border bg-white text-gray-700">Edit Data</button>
                                <button id="confirmContinueBtn" class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Lanjutkan</button>
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
    const closeBtn2 = document.getElementById('closeModalBtn2');
    const backdrop = modal ? modal.querySelector('[data-modal-backdrop]') : null;
    const confirmBtn = document.getElementById('confirmContinueBtn');
    const form = document.getElementById('orderDetailForm');

    // Form fields
    const fullNameInput = document.getElementById('fullName');
    const identityTypeInput = document.getElementById('identityType');
    const identityNumberInput = document.getElementById('identityNumber');
    const emailInput = document.getElementById('email');
    const whatsappInput = document.getElementById('whatsappNumber');

    // Hidden inputs
    const hiddenName = document.getElementById('hiddenName');
    const hiddenEmail = document.getElementById('hiddenEmail');
    const hiddenPhone = document.getElementById('hiddenPhone');
    const hiddenIdentityType = document.getElementById('hiddenIdentityType');
    const hiddenIdentityNumber = document.getElementById('hiddenIdentityNumber');
    const identityNumberError = document.getElementById('identityNumberError');

    if (!modal || !openBtn || !confirmBtn || !form) return;

    // Real-time identity number validation
    identityNumberInput.addEventListener('input', function() {
        const value = this.value.trim();
        if (value.length > 0 && value.length < 5) {
            identityNumberError.classList.remove('hidden');
        } else {
            identityNumberError.classList.add('hidden');
        }
    });

    function showModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        confirmBtn.focus();
        document.body.style.overflow = 'hidden';
        
        // Update modal display
        document.getElementById('modalName').textContent = fullNameInput.value.trim();
        document.getElementById('modalEmail').textContent = emailInput.value.trim();
        document.getElementById('modalPhone').textContent = '+62' + whatsappInput.value.trim();
    }

    function hideModal() {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        openBtn.focus();
    }

    openBtn.addEventListener('click', function (e) {
        e.preventDefault();
        // Validate form before showing modal
        if (!fullNameInput.value.trim()) {
            alert('Masukkan nama lengkap');
            return;
        }
        if (!identityTypeInput.value) {
            alert('Pilih jenis identitas');
            return;
        }
        if (!identityNumberInput.value.trim()) {
            alert('Masukkan nomor identitas');
            return;
        }
        if (!/^\d{5,}$/.test(identityNumberInput.value.trim())) {
            alert('Nomor identitas minimal 5 karakter');
            return;
        }
        if (!emailInput.value.trim()) {
            alert('Masukkan email');
            return;
        }
        if (!whatsappInput.value.trim()) {
            alert('Masukkan nomor WhatsApp');
            return;
        }
        showModal();
    });

    closeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        hideModal();
    });

    closeBtn2.addEventListener('click', function (e) {
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

    // when user confirms, populate hidden inputs and submit the form
    confirmBtn.addEventListener('click', function (e) {
        hiddenName.value = fullNameInput.value.trim();
        hiddenEmail.value = emailInput.value.trim();
        hiddenPhone.value = '+62' + whatsappInput.value.trim();
        hiddenIdentityType.value = identityTypeInput.value;
        hiddenIdentityNumber.value = identityNumberInput.value.trim();
        
        confirmBtn.disabled = true;
        form.submit();
    });
});
</script>

@endsection

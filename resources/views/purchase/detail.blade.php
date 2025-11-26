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

                <form action="{{ route('purchase.processDetail', $order->id) }}" method="POST" class="flex-1 ml-3">
                    @csrf
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                    <input type="hidden" name="phone" value="{{ auth()->user()->phone ?? '' }}">
                    <button type="submit"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold">
                        Lanjutkan
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>

@endsection

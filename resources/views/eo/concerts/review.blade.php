@extends('layouts.eo')

@section('content')
<div class="px-4 py-8 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-xl border border-gray-200">

        {{-- Title --}}
        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-gray-900 mb-6">
            Review Konser 
        </h2>

        {{-- Wrapper Poster + Info --}}
        <div class="flex flex-col md:grid md:grid-cols-2 gap-6">

            {{-- Poster --}}
            <div class="w-full">
                <img src="{{ asset('storage/' . $concert->image_url) }}"
                    class="rounded-xl w-full h-56 sm:h-72 md:h-80 object-cover border shadow-md"
                    alt="{{ $concert->title }}">
            </div>

            {{-- Info --}}
            <div class="space-y-4 text-gray-800">
                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Judul</p>
                    <p class="text-base sm:text-lg font-semibold">{{ $concert->title }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Lokasi</p>
                    <p class="text-sm sm:text-base">{{ $concert->location }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Tanggal</p>
                    <p class="text-sm sm:text-base">
                        {{ $concert->date->format('d M Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Status Approval</p>
                    <span class="px-3 py-1 text-xs rounded-full
                        @if($concert->approval_status === 'approved')
                            bg-green-100 text-green-700
                        @elseif($concert->approval_status === 'pending')
                            bg-yellow-100 text-yellow-700
                        @else
                            bg-gray-200 text-gray-700
                        @endif">
                        {{ ucfirst($concert->approval_status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Description --}}
        @if($concert->description)
        <div class="mt-8">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Deskripsi</h3>
            <p class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg text-sm sm:text-base">
                {{ $concert->description }}
            </p>
        </div>
        @endif

        {{-- Tickets List --}}
        <div class="mt-10">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Tipe Tiket 🎟️</h3>

            @if($concert->ticketTypes->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                @foreach($concert->ticketTypes as $t)
                <div class="p-4 rounded-lg border bg-white shadow-sm hover:shadow-md transition">
                    <h4 class="font-bold text-gray-900 text-sm sm:text-base">{{ $t->name }}</h4>
                    <p class="text-sm text-gray-600">Rp {{ number_format($t->price, 0, ',', '.') }}</p>
                    <p class="text-xs sm:text-sm text-gray-500">Kuota: {{ $t->quota }}</p>
                </div>
                @endforeach
            
            </div>
            @else
            <p class="text-gray-500 italic text-sm sm:text-base">
                Belum ada tiket ditambahkan.
            </p>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="mt-12 flex flex-col sm:flex-row justify-between gap-4">

            {{-- Back --}}
            <a href="{{ route('eo.concerts.tickets.index', $concert->id) }}"
                class="w-full sm:w-auto text-center px-5 py-3 bg-gray-100 hover:bg-gray-200
                rounded-lg font-medium shadow-sm text-gray-800 transition">
                ← Kembali Edit Tiket
            </a>

            {{-- Submit --}}
            @if($concert->ticketTypes->count() > 0)
            <form action="{{ route('eo.concerts.submit', $concert->id) }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button
                    class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700
                    text-white font-semibold rounded-lg shadow transition">
                    Kirim ke Admin untuk Review 🚀
                </button>
            </form>
            @else
            <button disabled
                class="w-full sm:w-auto px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed opacity-70">
                Tambahkan Tiket terlebih dahulu
            </button>
            @endif

        </div>

    </div>

</div>
@endsection

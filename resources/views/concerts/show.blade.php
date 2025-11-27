@extends('layouts.app')

@section('title', $concert->title)

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <img src="{{ asset('storage/' . $concert->image_url) }}" alt="{{ $concert->title }}"
             class="w-full h-96 object-cover">

        <div class="p-6">

            <h1 class="text-3xl font-bold mb-2">{{ $concert->title }}</h1>

            <!-- Info Lokasi + Tanggal + Status -->
            <div class="flex items-center space-x-4 text-gray-600 mb-4">
                <div>📍 {{ $concert->location }}</div>
                <div>📅 {{ $concert->date->format('d M Y') }}</div>

                {{-- STATUS TAMPIL --}}
                @if($concert->approval_status !== 'approved')
                    <div class="text-yellow-600 font-medium text-sm">Coming Soon ⏳</div>
                @else
                    @if($concert->selling_status === 'sold_out')
                        <div class="text-red-600 font-medium text-sm">Sold Out ❌</div>
                    @elseif($concert->selling_status === 'coming_soon')
                        <div class="text-yellow-600 font-medium text-sm">Coming Soon ⏳</div>
                    @else
                        <div class="text-green-600 font-medium text-sm">Tiket Tersedia ✔</div>
                    @endif
                @endif
            </div>

            <p class="text-gray-700 mb-6">
                Organizer: <strong>{{ $concert->organizer->organization_name ?? 'Unknown' }}</strong>
            </p>

            <div class="text-gray-800 mb-6">
                <div class="text-xs text-gray-500">Mulai dari</div>
                <div class="text-3xl font-extrabold text-orange-500">
                    Rp {{ number_format($concert->price, 0, ',', '.') }}
                </div>
            </div>

            {{-- Deskripsi Event --}}
            @if($concert->description)
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold mb-2">Tentang Event</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $concert->description }}</p>
                </div>
            @endif

            {{-- Tombol Buy Ticket --}}
            <div class="flex items-center space-x-4">
                <a href="{{ route('concerts.index') }}"
                   class="inline-flex items-center bg-gray-200 px-4 py-2 rounded-lg">
                    Back
                </a>

                @if ($concert->approval_status !== 'approved')
                    <button disabled
                        class="inline-flex items-center bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed">
                        Coming Soon
                    </button>

                @elseif ($concert->selling_status === 'sold_out')
                    <button disabled
                        class="inline-flex items-center bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed">
                        Sold Out
                    </button>

                @elseif ($concert->selling_status === 'coming_soon')
                    <button disabled
                        class="inline-flex items-center bg-yellow-500 text-white px-4 py-2 rounded-lg cursor-not-allowed">
                        Coming Soon
                    </button>

                @else {{-- AVAILABLE & APPROVED --}}
                    @guest
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg">
                            Buy Ticket
                        </a>
                    @else
                        <a href="{{ route('purchase.show', $concert->id) }}"
                           class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg">
                            Buy Ticket
                        </a>
                    @endguest
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

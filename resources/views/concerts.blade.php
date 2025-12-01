@extends('layouts.app')

@section('title', 'Concerts')

@section('content')
<!-- EVENT TERBARU SECTION -->
<section class="w-full bg-white py-14">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Header with search -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-900">Events Terbaru</h2>

            <div class="flex items-center space-x-4">
                <form method="GET" action="{{ route('concerts.index') }}" class="flex items-center space-x-2">
                    <input name="q" value="{{ request('q') }}" placeholder="Cari konser ..."
                        class="px-4 py-2 rounded-full w-64 focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    <button type="submit" class="text-white bg-indigo-600 px-4 py-2 rounded-full hover:bg-indigo-700">Cari</button>
                </form>
            </div>
        </div>

        <!-- Page Info -->
        @if($concerts->count() > 0 && $concerts->lastPage() > 1)
        <div class="text-sm text-gray-600 mb-4">
            Menampilkan <strong>{{ ($concerts->currentPage() - 1) * 12 + 1 }}</strong>–<strong>{{ min($concerts->currentPage() * 12, $concerts->total()) }}</strong> dari <strong>{{ $concerts->total() }}</strong> konser
        </div>
        @elseif($concerts->count() > 0)
        <div class="text-sm text-gray-600 mb-4">
            Menampilkan <strong>{{ $concerts->total() }}</strong> konser
        </div>
        @endif

        <!-- Event List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

            @forelse($concerts as $concert)
            @php $isSoldOut = isset($concert->selling_status) && $concert->selling_status === 'sold_out'; @endphp
            @if($isSoldOut)
            <div class="block bg-white border rounded-xl shadow transition overflow-hidden opacity-90 filter grayscale pointer-events-none cursor-not-allowed">
            @else
            <a href="{{ route('concerts.show', $concert->id) }}" class="block bg-white border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
            @endif
                <div class="relative">
                    <img src="{{ $concert->image_url ? asset($concert->image_url) : asset('images/default-event.png') }}" class="w-full h-56 object-cover" />
                    <!-- location & date moved below title (use FA icons) -->
                </div>

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $concert->title }}</h3>

                    <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot"></i>
                            <span class="truncate max-w-[12rem]">{{ $concert->location }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>{{ \Illuminate\Support\Carbon::parse($concert->date)->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between">
                        <div>
                            <div class="text-xs text-gray-500">Mulai dari</div>
                            <div class="text-2xl font-extrabold text-orange-500">Rp. {{ number_format($concert->price, 0, ',', '.') }}</div>
                        </div>

                        <div class="text-right">
                            @if($concert->approval_status !== 'approved')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Coming Soon</span>
                            @else
                            @if($concert->selling_status === 'sold_out')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Sold Out</span>
                            @elseif($concert->selling_status === 'coming_soon')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Coming Soon</span>
                            @else
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Tiket Tersedia</span>
                            @endif
                            @endif
                        </div>
                    </div>

                    <div class="border-t mt-4 pt-4 flex items-center space-x-3">

                        @php
                        $logo = $concert->organizer->url_logo ?? null;
                        @endphp

                        <img src="{{ $logo ? asset('foto/'.$logo) : asset('images/default-org.png') }}"
                            class="h-10 w-10 rounded-full object-cover border"
                            alt="Organizer Logo">

                        <div class="text-sm text-gray-700 font-medium">
                            {{ $concert->organizer->organization_name ?? 'Unknown Organizer' }}
                        </div>

                    </div>

                </div>
            @if($isSoldOut)
            </div>
            @else
            </a>
            @endif
            @empty
            <div class="col-span-1 sm:col-span-2 md:col-span-4">
                <div class="bg-white rounded-xl shadow p-8 text-center">
                    <span class="text-gray-400">(Belum ada data konser)</span>
                </div>
            </div>
            @endforelse

        </div>

        <!-- Pagination (numbered with jump by 5) -->
        @if($concerts->hasPages())
        @php
        $current = $concerts->currentPage();
        $last = $concerts->lastPage();
        $start = max(1, min($current - 2, max(1, $last - 4)));
        $end = min($last, max($current + 2, min(5, $last)));
        @endphp

        <div class="mt-10 flex justify-center">
            <nav class="flex items-center gap-2 text-sm" aria-label="Pagination">
                @php $jumpBack = max(1, $current - 5); @endphp
                <a href="{{ $concerts->url($jumpBack) }}" class="text-gray-500 hover:text-gray-700 px-2 py-1 rounded">««</a>

                @if($concerts->onFirstPage())
                <span class="text-gray-300 px-2 py-1">‹</span>
                @else
                <a href="{{ $concerts->previousPageUrl() }}" class="text-gray-500 hover:text-gray-700 px-2 py-1 rounded">‹</a>
                @endif

                @for($i = $start; $i <= $end; $i++)
                    @if($i==$current)
                    <span class="px-3 py-1 font-medium text-gray-900">{{ $i }}</span>
                    @else
                    <a href="{{ $concerts->url($i) }}" class="text-gray-600 hover:bg-gray-100 px-3 py-1 rounded">{{ $i }}</a>
                    @endif
                    @endfor

                    @if($concerts->hasMorePages())
                    <a href="{{ $concerts->nextPageUrl() }}" class="text-gray-500 hover:text-gray-700 px-2 py-1 rounded">›</a>
                    @else
                    <span class="text-gray-300 px-2 py-1">›</span>
                    @endif

                    @php $jumpForward = min($last, $current + 5); @endphp
                    <a href="{{ $concerts->url($jumpForward) }}" class="text-gray-500 hover:text-gray-700 px-2 py-1 rounded">»»</a>
            </nav>
        </div>
        @endif
    </div>
</section>

@include('partials.footer')

@endsection
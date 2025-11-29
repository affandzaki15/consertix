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
            <a href="{{ route('concerts.show', $concert->id) }}" class="block bg-white border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                <div class="relative">
                    <img src="{{ asset('storage/' . $concert->image_url) }}" class="w-full h-56 object-cover" />

                    <div class="absolute left-3 top-3 bg-white/80 backdrop-blur-sm text-xs font-semibold text-gray-800 rounded-md px-3 py-1 flex items-center gap-2">
                        <span class="text-sm">📍</span>
                        <span class="truncate max-w-[10rem]">{{ $concert->location }}</span>
                    </div>

                    <div class="absolute right-3 top-3 bg-indigo-600 text-white text-xs font-semibold rounded-md px-3 py-1">
                        {{ \Illuminate\Support\Carbon::parse($concert->date)->format('d M Y') }}
                    </div>
                </div>

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $concert->title }}</h3>

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
                        <img src="{{ asset('logo/user.png') }}" class="h-10 w-10 rounded-full object-cover" alt="Organizer">
                        <div class="text-sm text-gray-700 font-medium">{{ $concert->organizer->organization_name ?? 'Unknown Organizer' }}</div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-1 sm:col-span-2 md:col-span-4">
                <div class="bg-white rounded-xl shadow p-8 text-center">
                    <span class="text-gray-400">(Belum ada data konser)</span>
                </div>
            </div>
            @endforelse

        </div>

        <!-- Pagination -->
        <div class="mt-10 flex justify-center">
            {{ $concerts->links() }}
        </div>
    </div>
</section>



@endsection
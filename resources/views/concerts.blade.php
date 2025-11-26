@extends('layouts.app')

@section('title', 'Concerts')

@section('content')
<!-- EVENT TERBARU SECTION -->
<section class="w-full bg-white py-14">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Header with search -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-900">Event Terbaru</h2>

            <div class="flex items-center space-x-4">
                <form method="GET" action="{{ route('concerts.index') }}" class="flex items-center space-x-2">
                    <input name="q" value="{{ request('q') }}" placeholder="Cari konser ..."
                        class="px-4 py-2 rounded-full w-64 focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    <button type="submit" class="text-white bg-indigo-600 px-4 py-2 rounded-full hover:bg-indigo-700">Cari</button>
                </form>
            </div>
        </div>

        <!-- Event List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

            @forelse($concerts as $concert)
                <a href="{{ route('concerts.show', $concert->id) }}" class="block border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <img src="{{ $concert->image_url }}" class="w-full h-56 object-cover rounded-t-xl" />

                    <div class="p-4">
                        <div class="flex items-center text-gray-600 text-sm space-x-2">
                            <span>📍 {{ $concert->location }}</span>
                        </div>

                        <div class="flex items-center text-gray-600 text-sm space-x-2">
                            <span>📅 {{ \Illuminate\Support\Carbon::parse($concert->date)->format('d M Y') }}</span>
                        </div>

                        <h3 class="mt-2 text-lg font-semibold">{{ $concert->title }}</h3>

                        <p class="text-sm mt-2 text-gray-700">
                            Mulai dari <span class="text-red-600 font-bold">Rp. {{ number_format($concert->price, 0, ',', '.') }}</span>
                        </p>

                        <span class="text-green-600 font-medium text-sm">{{ $concert->status }}</span>
                        
                        <!-- Organizer (logo + name) -->
                        <div class="border-t mt-4 pt-4 flex items-center space-x-3">
                            <img src="{{ $concert->image_url }}" alt="{{ $concert->organizer }}" class="h-10 w-10 rounded-full object-cover"> 
                            <div class="text-sm text-gray-700 font-medium">{{ $concert->organizer }}</div>
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
    </div>
</section>



@endsection


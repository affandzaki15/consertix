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
        @if($concerts->count() > 0)
        <div class="text-sm text-gray-600 mb-4">
            Menampilkan <strong>{{ ($concerts->currentPage() - 1) * 12 + 1 }}</strong> hingga <strong>{{ min($concerts->currentPage() * 12, $concerts->total()) }}</strong> dari <strong>{{ $concerts->total() }}</strong> konser
        </div>
        @endif

        <!-- Event List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

            @forelse($concerts as $concert)
            <a href="{{ route('concerts.show', $concert->id) }}" class="block border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                <img src="{{ asset('storage/' . $concert->image_url) }}" class="w-full h-56 object-cover rounded-t-xl" />

                
                    <div class="flex items-center text-gray-600 text-sm space-x-2">
                        <span>📍 {{ $concert->location }}</span>
                    </div>

                    <div class="flex items-center text-gray-600 text-sm space-x-2">
                        <span>📅 {{ \Illuminate\Support\Carbon::parse($concert->date)->format('d M Y') }}</span>
                    </div>

                    <h3 class="mt-2 text-lg font-semibold">{{ $concert->title }}</h3>

                    <div class="mt-2 flex items-center justify-between">
                        <div>
                            <div class="text-xs text-gray-500">Mulai dari</div>
                            <div class="text-2xl font-extrabold text-orange-500">
                                Rp. {{ number_format($concert->price, 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- Logic status tampil --}}
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

                    <!-- Organizer -->
                    <div class="border-t mt-4 pt-4 flex items-center space-x-3">
                        <img src="{{ asset('logo/user.png') }}" class="h-10 w-10 rounded-full object-cover" alt="Organizer">
                        <div class="text-sm text-gray-700 font-medium">
                            {{ $concert->organizer->organization_name ?? 'Unknown Organizer' }}
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
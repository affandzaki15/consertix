@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Header Section -->
    <div class="mb-12">
        <div class="flex items-center gap-8 mb-8">
            <!-- Logo -->
            <div class="w-32 h-32 rounded-lg overflow-hidden bg-white shadow flex items-center justify-center flex-shrink-0">
                @if($organizer->url_logo ?? false)
                    <img src="{{ asset('storage/' . $organizer->url_logo) }}" alt="{{ $organizer->organization_name }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ asset('logo/header.png') }}" alt="logo" class="w-full h-full object-contain p-4">
                @endif
            </div>

            <!-- Info -->
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6">{{ $organizer->organization_name ?? 'Organizer' }}</h1>
                <div class="flex gap-12">
                    <div>
                        <div class="text-sm text-gray-500">Total Event</div>
                        <div class="text-2xl font-semibold text-gray-900">{{ $organizer->concerts->count() ?? 0 }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Bergabung pada</div>
                        <div class="text-2xl font-semibold text-gray-900">{{ optional($organizer->created_at)->format('F Y') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="mt-8">
            <nav class="flex gap-3 border-b border-gray-200">
                <button id="tab-active" class="px-6 py-3 rounded-t-lg bg-gray-50 border-b-2 border-gray-900 text-gray-900 font-semibold">Event</button>
            </nav>
        </div>
    </div>

    <!-- Events Grid -->
    <div id="events-active" class="events-grid">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $active = $organizer->concerts->filter(function($c){ return 
                    isset($c->selling_status) ? $c->selling_status !== 'past' : 
                    (
                        isset($c->date) ? 
                        \Illuminate\Support\Carbon::parse($c->date)->isFuture() : true
                    );
                });
            @endphp

            @forelse($active as $concert)
                <a href="{{ route('concerts.show', $concert->id) }}" class="block bg-white border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <div class="relative">
                        <img src="{{ $concert->image_url ? asset('storage/' . $concert->image_url) : ( $concert->image ?? '') }}" class="w-full h-56 object-cover" />

                        <div class="absolute left-3 top-3 bg-white/80 backdrop-blur-sm text-xs font-semibold text-gray-800 rounded-md px-3 py-1 flex items-center gap-2">
                            <span class="text-sm">📍</span>
                            <span class="truncate max-w-[10rem]">{{ $concert->location }}</span>
                        </div>

                        <div class="absolute right-3 top-3 bg-indigo-600 text-white text-xs font-semibold rounded-md px-3 py-1">
                            {{ \Illuminate\Support\Carbon::parse($concert->date)->format('d M Y') }}
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ Str::limit($concert->title, 36) }}</h3>

                        <div class="mt-3 flex items-center justify-between">
                            <div>
                                <div class="text-xs text-gray-500">Mulai dari</div>
                                <div class="text-2xl font-extrabold text-orange-500">Rp. {{ number_format($concert->price ?? 0, 0, ',', '.') }}</div>
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

                            <img src="{{ $logo ? asset('storage/'.$logo) : asset('images/default-org.png') }}"
                                class="h-10 w-10 rounded-full object-cover border"
                                alt="Organizer Logo">

                            <div class="text-sm text-gray-700 font-medium">
                                {{ $concert->organizer->organization_name ?? 'Unknown Organizer' }}
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-4">
                    <div class="bg-white rounded-xl shadow p-8 text-center">
                        <span class="text-gray-500">Belum ada event aktif.</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div id="events-past" class="events-grid hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $past = $organizer->concerts->filter(function($c){
                    return isset($c->selling_status) ? $c->selling_status === 'past' : (isset($c->date) ? \Carbon\Carbon::parse($c->date)->isPast() : false);
                });
            @endphp

            @forelse($past as $concert)
                <a href="{{ route('concerts.show', $concert->id) }}" class="block bg-white border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <div class="relative">
                        <img src="{{ $concert->image_url ? asset('storage/' . $concert->image_url) : ( $concert->image ?? '') }}" class="w-full h-56 object-cover" />

                        <div class="absolute left-3 top-3 bg-white/80 backdrop-blur-sm text-xs font-semibold text-gray-800 rounded-md px-3 py-1 flex items-center gap-2">
                            <span class="text-sm">📍</span>
                            <span class="truncate max-w-[10rem]">{{ $concert->location }}</span>
                        </div>

                        <div class="absolute right-3 top-3 bg-indigo-600 text-white text-xs font-semibold rounded-md px-3 py-1">
                            {{ \Illuminate\Support\Carbon::parse($concert->date)->format('d M Y') }}
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ Str::limit($concert->title, 36) }}</h3>

                        <div class="mt-3 flex items-center justify-between">
                            <div>
                                <div class="text-xs text-gray-500">Mulai dari</div>
                                <div class="text-2xl font-extrabold text-gray-900">Rp. {{ number_format($concert->price ?? 0, 0, ',', '.') }}</div>
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

                            <img src="{{ $logo ? asset('storage/'.$logo) : asset('images/default-org.png') }}"
                                class="h-10 w-10 rounded-full object-cover border"
                                alt="Organizer Logo">

                            <div class="text-sm text-gray-700 font-medium">
                                {{ $concert->organizer->organization_name ?? 'Unknown Organizer' }}
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-4">
                    <div class="bg-white rounded-xl shadow p-8 text-center">
                        <span class="text-gray-500">Belum ada event lalu.</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    (function(){
        var tabActive = document.getElementById('tab-active');
        var tabPast = document.getElementById('tab-past');
        var eventsActive = document.getElementById('events-active');
        var eventsPast = document.getElementById('events-past');

        function setActive(){
            tabActive.classList.add('bg-gray-50','border-b-2','border-gray-900','text-gray-900');
            tabActive.classList.remove('border-b-2','border-transparent','text-gray-600');
            tabPast.classList.remove('bg-gray-50','border-b-2','border-gray-900','text-gray-900');
            tabPast.classList.add('border-b-2','border-transparent','text-gray-600');
            eventsActive.classList.remove('hidden');
            eventsPast.classList.add('hidden');
        }

        function setPast(){
            tabPast.classList.add('bg-gray-50','border-b-2','border-gray-900','text-gray-900');
            tabPast.classList.remove('border-b-2','border-transparent','text-gray-600');
            tabActive.classList.remove('bg-gray-50','border-b-2','border-gray-900','text-gray-900');
            tabActive.classList.add('border-b-2','border-transparent','text-gray-600');
            eventsPast.classList.remove('hidden');
            eventsActive.classList.add('hidden');
        }

        tabActive.addEventListener('click', setActive);
        tabPast.addEventListener('click', setPast);
    })();
</script>

@endsection

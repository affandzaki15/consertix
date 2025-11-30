@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        <!-- Left: logo and summary -->
        <div class="md:col-span-1">
            <div class="flex items-center gap-6">
                <div class="w-36 h-36 rounded-full overflow-hidden bg-white shadow flex items-center justify-center">
                    @if($organizer->logo_url ?? false)
                        <img src="{{ $organizer->logo_url }}" alt="{{ $organizer->organization_name }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('logo/header.png') }}" alt="logo" class="w-full h-full object-contain">
                    @endif
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $organizer->organization_name ?? 'Organizer' }}</h1>
                    <div class="mt-3 text-sm text-gray-500 flex gap-6">
                        <div>
                            <div class="text-xs text-gray-400">Total Event</div>
                            <div class="text-xl font-semibold text-gray-900">{{ $organizer->concerts->count() ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400">Bergabung pada</div>
                            <div class="text-xl font-semibold text-gray-900">{{ optional($organizer->created_at)->format('F Y') ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mt-8">
                <nav class="flex gap-3">
                    <button id="tab-active" class="px-4 py-2 rounded-lg bg-white border text-gray-800 shadow">Event Aktif</button>
                    <button id="tab-past" class="px-4 py-2 rounded-lg bg-white/50 border text-gray-600">Event Lalu</button>
                </nav>
            </div>
        </div>

        <!-- Right: events grid spanning two columns -->
        <div class="md:col-span-2">
            <div id="events-active" class="events-grid">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
                                <img src="{{ $concert->image_url ? asset('storage/' . $concert->image_url) : ( $concert->image ?? '') }}" class="w-full h-44 object-cover rounded-t-xl" />
                            </div>

                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ Str::limit($concert->title, 36) }}</h3>
                                <div class="mt-3 flex items-center text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/></svg>
                                    <span>{{ optional($concert->date)->format('d F Y') ?? (isset($concert->date) ? \Carbon\Carbon::parse($concert->date)->format('d F Y') : '-') }}</span>
                                </div>

                                <div class="mt-4 text-xl font-extrabold text-orange-500">Rp. {{ number_format($concert->price ?? 0, 0, ',', '.') }}</div>

                                <div class="border-t mt-4 pt-4 flex items-center gap-3">
                                    <img src="{{ asset('logo/user.png') }}" class="h-8 w-8 rounded-full object-cover" alt="Organizer">
                                    <div class="text-sm text-gray-700">{{ $organizer->organization_name ?? 'Organizer' }}</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-1 sm:col-span-2">
                            <div class="bg-white rounded-xl shadow p-8 text-center">
                                <span class="text-gray-500">Belum ada event aktif.</span>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div id="events-past" class="events-grid hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @php
                        $past = $organizer->concerts->filter(function($c){
                            return isset($c->selling_status) ? $c->selling_status === 'past' : (isset($c->date) ? \Carbon\Carbon::parse($c->date)->isPast() : false);
                        });
                    @endphp

                    @forelse($past as $concert)
                        <a href="{{ route('concerts.show', $concert->id) }}" class="block bg-white border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                            <div class="relative">
                                <img src="{{ $concert->image_url ? asset('storage/' . $concert->image_url) : ( $concert->image ?? '') }}" class="w-full h-44 object-cover rounded-t-xl" />
                            </div>

                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ Str::limit($concert->title, 36) }}</h3>
                                <div class="mt-3 flex items-center text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/></svg>
                                    <span>{{ optional($concert->date)->format('d F Y') ?? (isset($concert->date) ? \Carbon\Carbon::parse($concert->date)->format('d F Y') : '-') }}</span>
                                </div>

                                <div class="mt-4 text-xl font-extrabold text-gray-900">Rp. {{ number_format($concert->price ?? 0, 0, ',', '.') }}</div>

                                <div class="border-t mt-4 pt-4 flex items-center gap-3">
                                    <img src="{{ asset('logo/user.png') }}" class="h-8 w-8 rounded-full object-cover" alt="Organizer">
                                    <div class="text-sm text-gray-700">{{ $organizer->organization_name ?? 'Organizer' }}</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-1 sm:col-span-2">
                            <div class="bg-white rounded-xl shadow p-8 text-center">
                                <span class="text-gray-500">Belum ada event lalu.</span>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
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
            tabActive.classList.add('bg-white','text-gray-900');
            tabActive.classList.remove('bg-white/50','text-gray-600');
            tabPast.classList.remove('bg-white','text-gray-900');
            tabPast.classList.add('bg-white/50','text-gray-600');
            eventsActive.classList.remove('hidden');
            eventsPast.classList.add('hidden');
        }

        function setPast(){
            tabPast.classList.add('bg-white','text-gray-900');
            tabPast.classList.remove('bg-white/50','text-gray-600');
            tabActive.classList.remove('bg-white','text-gray-900');
            tabActive.classList.add('bg-white/50','text-gray-600');
            eventsPast.classList.remove('hidden');
            eventsActive.classList.add('hidden');
        }

        tabActive.addEventListener('click', setActive);
        tabPast.addEventListener('click', setPast);
    })();
</script>

@endsection

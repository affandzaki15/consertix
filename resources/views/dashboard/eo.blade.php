@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 px-6 py-8 text-gray-900">
    
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">EO Dashboard</h1>
                <p class="text-gray-500 mt-1">Kelola konser yang kamu selenggarakan dengan mudah ✨</p>
            </div>

            <a href="{{ route('concerts.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 transition px-5 py-2.5 rounded-lg font-semibold text-white shadow-md">
                + Buat Konser Baru
            </a>
        </div>


        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm">Total Concerts</p>
                <h2 class="text-3xl font-bold mt-2 text-indigo-600">{{ $totalConcerts }}</h2>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm">Tiket Terjual</p>
                <h2 class="text-3xl font-bold mt-2 text-green-600">{{ $totalSold }}</h2>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm">Pendapatan</p>
                <h2 class="text-2xl font-bold mt-2 text-yellow-600">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h2>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm">Pending Approval</p>
                <h2 class="text-3xl font-bold mt-2 text-red-600">{{ $statusStats['pending'] }}</h2>
            </div>

        </div>


        {{-- TABLE --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Daftar Konser Kamu</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-gray-600">
                            <th class="py-3 px-3 font-semibold">Judul Konser</th>
                            <th class="py-3 px-3 font-semibold">Status</th>
                            <th class="py-3 px-3 font-semibold">Terjual</th>
                            <th class="py-3 px-3 font-semibold">Pendapatan</th>
                            <th class="py-3 px-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700">
                        @forelse ($concerts as $c)
                        <tr class="border-b">
                            <td class="py-4 px-3 font-medium">{{ $c->title }}</td>

                            <td class="px-3">
                                <span class="px-3 py-1 rounded-full text-xs
                                    {{ $c->status == 'approved' ? 'bg-green-100 text-green-700' :
                                       ($c->status == 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                        'bg-gray-100 text-gray-700') }}">
                                    {{ ucfirst($c->status) }}
                                </span>
                            </td>

                            <td class="px-3 text-indigo-600 font-semibold">{{ $c->ticketTypes->sum('sold') }}</td>

                            <td class="px-3 text-green-600 font-semibold">
                                Rp {{ number_format($c->ticketTypes->sum(fn($t) => $t->price * $t->sold), 0, ',', '.') }}
                            </td>

                            <td class="px-3 text-center space-x-2">
                                <a href="{{ route('concerts.edit',$c->id) }}"
                                   class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-xs">
                                   Edit
                                </a>
                                <a href="{{ route('concerts.tickets.index',$c->id) }}"
                                   class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-xs">
                                   Tiket
                                </a>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-400">
                                Belum ada konser. Klik tombol <strong>“Buat Konser Baru”</strong>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>
</div>

@endsection

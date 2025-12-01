@extends('layouts.app')

@section('content')
@includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Konser</h1>
            <p class="text-gray-600">Kelola konser yang menunggu persetujuan dan riwayat aksi Anda.</p>
        </div>

        <!-- Section 1: Konser Menunggu Persetujuan -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-semibold text-gray-800">Konser Menunggu Persetujuan</h2>
                <span class="text-sm text-gray-500">
                    {{ $pending->total() }} item
                </span>
            </div>

            @if($pending->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-600">Tidak ada konser menunggu persetujuan.</p>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pending as $c)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $c->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $c->date ? \Carbon\Carbon::parse($c->date)->format('d M Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs">
                                    <div class="truncate" title="{{ $c->location }}">{{ $c->location ?? '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <!-- ✅ TOMBOL LIHAT -->
                                        <a href="{{ route('admin.concerts.show', $c) }}"
                                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs rounded-lg font-medium transition shadow-sm hover:shadow">
                                            Lihat
                                        </a>

                                        <!-- Tombol Setujui -->
                                        <form action="{{ route('admin.concerts.approve', $c) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg font-medium transition shadow-sm hover:shadow"
                                                onclick="return confirm('Yakin ingin menyetujui konser ini?')">
                                                Setujui
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak dengan catatan -->
                                        <form action="{{ route('admin.concerts.reject', $c) }}" method="POST" class="inline flex items-center">
                                            @csrf
                                            <input type="text" name="note"
                                                placeholder="Catatan (opsional)"
                                                class="border border-gray-300 rounded-lg px-2.5 py-1 text-xs text-gray-700 mr-2 focus:outline-none focus:ring-2 focus:ring-red-200 min-w-[120px] max-w-[180px]">
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs rounded-lg font-medium transition shadow-sm hover:shadow"
                                                onclick="return confirm('Yakin ingin menolak konser ini?')">
                                                Tolak
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($pending->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $pending->appends(request()->except('pending_page', 'history_page'))->links() }}
                </div>
                @endif
            </div>
            @endif
        </section>

        <!-- Section 2: Riwayat Konser (Approved / Rejected) -->
        <section>
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-semibold text-gray-800">Riwayat Konser</h2>
                <span class="text-sm text-gray-500">
                    {{ $history->total() }} item
                </span>
            </div>

            @if($history->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-600">Belum ada riwayat konser yang disetujui atau ditolak.</p>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($history as $concert)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $concert->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $concert->date ? \Carbon\Carbon::parse($concert->date)->format('d M Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $status = $concert->approval_status ?? $concert->status ?? 'unknown';
                                    @endphp
                                    @if($status === 'approved')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs rounded-full bg-green-100 text-green-800 font-medium">
                                        Disetujui
                                    </span>
                                    @elseif($status === 'rejected')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs rounded-full bg-red-100 text-red-800 font-medium">
                                        Ditolak
                                    </span>
                                    @else
                                    <span class="px-2.5 py-0.5 inline-flex text-xs rounded-full bg-gray-100 text-gray-800 font-medium">
                                        {{ ucfirst($status) }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.concerts.show', $concert) }}"
                                        class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                                        Lihat Detail
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($history->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $history->appends(request()->except('pending_page', 'history_page'))->links() }}
                </div>
                @endif
            </div>
            @endif
        </section>
    </div>
</div>
@endsection
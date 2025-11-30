@extends('layouts.app')

@section('content')
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('admin.concerts.pending') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0L4.586 11l3.707-3.707a1 1 0 111.414 1.414L7.414 11l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Detail Konser</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap dan riwayat aksi admin</p>
            </div>

            <!-- Info Konser -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $concert->title }}</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($concert->approval_status === 'approved') bg-green-100 text-green-800
                        @elseif($concert->approval_status === 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($concert->approval_status === 'approved') Disetujui
                        @elseif($concert->approval_status === 'rejected') Ditolak
                        @else Menunggu Persetujuan @endif
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Lokasi</p>
                        <p class="text-gray-800">{{ $concert->location ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal & Waktu</p>
                        <p class="text-gray-800">
                            @if($concert->date)
                                {{ $concert->date->format('d M Y') }}
                                @if($concert->time)
                                    • {{ $concert->time->format('H:i') }}
                                @endif
                            @else
                                —
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status Tiket</p>
                        <p class="text-gray-800">{{ $concert->selling_status ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Harga Mulai</p>
                        <p class="text-gray-800">Rp {{ number_format($concert->price ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($concert->description)
                    <div class="mt-6">
                        <p class="text-sm text-gray-500 mb-1">Deskripsi</p>
                        <p class="text-gray-700">{{ $concert->description }}</p>
                    </div>
                @endif

                @if($concert->notes)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500 mb-1">Catatan Admin</p>
                        <p class="text-gray-700 italic">“{{ $concert->notes }}”</p>
                    </div>
                @endif
            </div>

            <!-- Riwayat Aksi Admin -->
            @if($concert->adminActions->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Aksi Admin</h3>
                    <div class="space-y-4">
                        @foreach($concert->adminActions->sortByDesc('created_at') as $action)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    @if($action->action === 'approved')
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-800">
                                        <span class="font-medium">{{ $action->admin->name ?? 'Admin' }}</span>
                                        {{ $action->action === 'approved' ? 'menyetujui' : 'menolak' }} konser
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $action->created_at->format('d M Y, H:i') }}</p>
                                    @if($action->note)
                                        <p class="text-sm text-gray-700 mt-1 bg-gray-50 p-2 rounded-lg">
                                            <span class="font-medium text-gray-600">Catatan:</span> {{ $action->note }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
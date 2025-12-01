@extends('layouts.app')

@section('content')
    <style>
        /* Animasi untuk baris tabel */
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated-row {
            opacity: 0;
            animation: fadeInSlide 0.5s ease-out forwards;
            animation-delay: calc(0.08s * var(--delay));
        }

        /* Hover effect */
        .animated-row:hover {
            background-color: #f9fafb;
            transition: background-color 0.2s;
        }

        /* Tombol aksi */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            font-weight: 600;
            border-radius: 0.5rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        /* Input note */
        .note-input {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.375rem 0.5rem;
            font-size: 0.75rem;
            min-width: 130px;
            max-width: 180px;
        }
        .note-input:focus {
            outline: none;
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.2);
        }

        /* Badge status */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>

    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-slate-800">Manajemen Konser</h1>
                <p class="text-slate-600 mt-1">Kelola konser yang menunggu persetujuan dan riwayat aksi Anda.</p>
            </div>

            <!-- Section 1: Konser Menunggu Persetujuan -->
            <section class="mb-14">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-2">
                    <h2 class="text-xl font-semibold text-slate-800">Konser Menunggu Persetujuan</h2>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-sm rounded-full">
                        {{ $pending->total() }} item
                    </span>
                </div>

                @if($pending->isEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-slate-600">Tidak ada konser menunggu persetujuan.</p>
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Lokasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($pending as $index => $c)
                                        <tr class="animated-row" style="--delay: {{ $index * 0.08 }};">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-slate-800">{{ $c->title }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-700">
                                                {{ $c->date ? \Carbon\Carbon::parse($c->date)->translatedFormat('d M Y') : '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-700 max-w-xs">
                                                <div class="truncate" title="{{ $c->location }}">{{ $c->location ?? '—' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <!-- Lihat -->
                                                    <a href="{{ route('admin.concerts.show', $c) }}"
                                                       class="action-btn bg-indigo-600 hover:bg-indigo-700 text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Lihat
                                                    </a>

                                                    <!-- Setujui -->
                                                    <form action="{{ route('admin.concerts.approve', $c) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="action-btn bg-teal-600 hover:bg-teal-700 text-white"
                                                                onclick="return confirm('Yakin ingin menyetujui konser ini?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Setujui
                                                        </button>
                                                    </form>

                                                    <!-- Tolak -->
                                                    <form action="{{ route('admin.concerts.reject', $c) }}" method="POST" class="inline flex items-center gap-2">
                                                        @csrf
                                                        <input type="text" name="note"
                                                               placeholder="Catatan (opsional)"
                                                               class="note-input text-slate-700"
                                                               maxlength="100">
                                                        <button type="submit"
                                                                class="action-btn bg-rose-600 hover:bg-rose-700 text-white"
                                                                onclick="return confirm('Yakin ingin menolak konser ini?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
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
                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                                {{ $pending->appends(request()->except('pending_page', 'history_page'))->links() }}
                            </div>
                        @endif
                    </div>
                @endif
            </section>

            <!-- Section 2: Riwayat Konser -->
            <section>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-2">
                    <h2 class="text-xl font-semibold text-slate-800">Riwayat Konser</h2>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-sm rounded-full">
                        {{ $history->total() }} item
                    </span>
                </div>

                @if($history->isEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-slate-600">Belum ada riwayat konser yang disetujui atau ditolak.</p>
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($history as $index => $concert)
                                        <tr class="animated-row" style="--delay: {{ ($index + $pending->count()) * 0.08 }};">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-slate-800">{{ $concert->title }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-700">
                                                {{ $concert->date ? \Carbon\Carbon::parse($concert->date)->translatedFormat('d M Y') : '—' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $status = $concert->approval_status ?? $concert->status ?? 'unknown';
                                                @endphp
                                                @if($status === 'approved')
                                                    <span class="status-badge bg-teal-100 text-teal-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Disetujui
                                                    </span>
                                                @elseif($status === 'rejected')
                                                    <span class="status-badge bg-rose-100 text-rose-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Ditolak
                                                    </span>
                                                @else
                                                    <span class="status-badge bg-slate-100 text-slate-800">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('admin.concerts.show', $concert) }}"
                                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                                    Detail
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                                {{ $history->appends(request()->except('pending_page', 'history_page'))->links() }}
                            </div>
                        @endif
                    </div>
                @endif
            </section>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'EventFlow') }} — Manajemen Acara Profesional
        </div>
    </footer>
@endsection
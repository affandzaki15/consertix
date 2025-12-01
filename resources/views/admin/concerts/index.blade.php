@extends('layouts.app')

@section('content')
    <style>
        /* Animasi baris tabel */
        @keyframes fadeInRow {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .concert-row {
            opacity: 0;
            animation: fadeInRow 0.5s ease-out forwards;
            animation-delay: calc(0.07s * var(--delay));
        }

        /* Hover effect pada baris */
        .concert-row:hover {
            background-color: #f9fafb;
            transition: background-color 0.2s;
        }

        /* Tombol aksi */
        .action-link {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 600;
            transition: color 0.2s;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
        }
        .action-link:hover {
            background-color: rgba(129, 140, 248, 0.1);
        }

        /* Modal */
        .modal-fade {
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.25s ease;
        }
        .modal-fade.active {
            opacity: 1;
            transform: scale(1);
        }
    </style>

    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Semua Konser</h2>
                <a href="{{ route('admin.concerts.pending') }}"
                   class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Lihat Konser Pending
                </a>
            </div>

            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($concerts as $index => $concert)
                                <tr class="concert-row" style="--delay: {{ $index * 0.07 }};">
                                    <td class="px-5 py-4 font-medium text-gray-900">{{ $concert->title }}</td>
                                    <td class="px-5 py-4 text-gray-700">
                                        {{ $concert->date ? $concert->date->translatedFormat('d F Y') : '—' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $status = $concert->approval_status ?? $concert->status ?? 'pending';
                                        @endphp
                                        @if($status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Disetujui
                                            </span>
                                        @elseif($status === 'rejected')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-rose-100 text-rose-800 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <!-- Lihat Detail -->
                                            <a href="{{ route('admin.concerts.show', $concert) }}"
                                               class="action-link text-indigo-600 hover:text-indigo-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat
                                            </a>

                                            @if($status === 'pending')
                                                <!-- Approve -->
                                                <form action="{{ route('admin.concerts.approve', $concert) }}" method="POST" style="display:inline;" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="action-link text-teal-600 hover:text-teal-800"
                                                            onclick="return confirm('Yakin ingin menyetujui konser ini?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Approve
                                                    </button>
                                                </form>

                                                <!-- Reject -->
                                                <button type="button"
                                                        class="action-link text-rose-600 hover:text-rose-800"
                                                        onclick="openRejectModal({{ $concert->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Reject
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M9 11a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Belum ada konser terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $concerts->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl w-full max-w-md modal-fade">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tolak Konser</h3>
                <form id="rejectForm" method="POST">
                    @csrf
                    <input type="hidden" id="rejectConcertId" name="concert_id">
                    <textarea name="note"
                              placeholder="Catatan penolakan (opsional)"
                              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-rose-300 focus:border-rose-500"
                              rows="3"></textarea>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button"
                                class="px-4 py-2 text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition"
                                onclick="closeRejectModal()">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition">
                            Tolak Konser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'EventFlow') }} — Platform Manajemen Event Profesional
        </div>
    </footer>

    <script>
        function openRejectModal(concertId) {
            document.getElementById('rejectConcertId').value = concertId;
            document.getElementById('rejectForm').action = '/admin/concerts/' + concertId + '/reject';
            const modal = document.getElementById('rejectModal');
            modal.classList.remove('hidden');
            setTimeout(() => modal.querySelector('.modal-fade').classList.add('active'), 10);
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.querySelector('.modal-fade').classList.remove('active');
            setTimeout(() => modal.classList.add('hidden'), 250);
        }

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRejectModal();
        });
    </script>
@endsection
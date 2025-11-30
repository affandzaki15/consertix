@extends('layouts.app')

@section('content')
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Semua Konser</h2>
                <a href="{{ route('admin.concerts.pending') }}" class="text-blue-600 hover:underline">
                    Lihat Konser Pending
                </a>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Judul</th>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($concerts as $concert)
                            <tr>
                                <td class="px-4 py-2">{{ $concert->title }}</td>
                                <td class="px-4 py-2">
                                    @if($concert->date)
                                        {{ $concert->date->format('d M Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @php
                                        $status = $concert->approval_status ?? $concert->status ?? 'pending';
                                    @endphp
                                    @if($status === 'approved')
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            Disetujui
                                        </span>
                                    @elseif($status === 'rejected')
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 space-x-2">
                                    <!-- Lihat Detail -->
                                    <a href="{{ route('admin.concerts.show', $concert) }}"
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Lihat
                                    </a>

                                    @php
                                        $status = $concert->approval_status ?? $concert->status ?? 'pending';
                                    @endphp

                                    <!-- Approve / Reject hanya jika pending -->
                                    @if($status === 'pending')
                                        <!-- Approve -->
                                        <form action="{{ route('admin.concerts.approve', $concert) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-600 hover:text-green-900 text-sm font-medium"
                                                    onclick="return confirm('Yakin ingin menyetujui konser ini?')">
                                                Approve
                                            </button>
                                        </form>

                                        <!-- Reject -->
                                        <button type="button"
                                                class="text-red-600 hover:text-red-900 text-sm font-medium"
                                                onclick="openRejectModal({{ $concert->id }})">
                                            Reject
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada konser.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $concerts->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tolak Konser</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" id="rejectConcertId" name="concert_id">
                <textarea name="note"
                          placeholder="Catatan penolakan (opsional)"
                          class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-red-300"
                          rows="3"></textarea>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button"
                            class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-100"
                            onclick="closeRejectModal()">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(concertId) {
            document.getElementById('rejectConcertId').value = concertId;
            document.getElementById('rejectForm').action = '/admin/concerts/' + concertId + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Tutup modal saat klik di luar
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
@endsection
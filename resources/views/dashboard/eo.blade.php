@extends('layouts.eo')

@section('content')

<div class="min-h-screen bg-gray-100 px-4 sm:px-6 py-8 text-gray-900">

    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">EO Dashboard</h1>
                <p class="text-gray-500 mt-1">Kelola konser kamu dengan mudah ✨</p>
            </div>

            <a href="{{ route('eo.concerts.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 transition px-5 py-2.5 rounded-lg font-semibold text-white shadow-md whitespace-nowrap">
                + Buat Konser Baru
            </a>
        </div>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">

            <div class="bg-white rounded-xl p-5 shadow-sm border text-center">
                <p class="text-gray-500 text-xs sm:text-sm">Total Concerts</p>
                <h2 class="text-xl sm:text-3xl font-bold mt-1 text-indigo-600">{{ $totalConcerts }}</h2>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border text-center">
                <p class="text-gray-500 text-xs sm:text-sm">Tiket Terjual</p>
                <h2 class="text-xl sm:text-3xl font-bold mt-1 text-green-600">{{ $totalSold }}</h2>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border text-center">
                <p class="text-gray-500 text-xs sm:text-sm">Pendapatan</p>
                <h2 class="text-lg sm:text-2xl font-bold mt-1 text-yellow-600">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h2>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border text-center">
                <p class="text-gray-500 text-xs sm:text-sm">Pending Approval</p>
                <h2 class="text-xl sm:text-3xl font-bold mt-1 text-red-600">{{ $statusStats['pending'] }}</h2>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border overflow-x-auto">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Daftar Konser Kamu</h3>

            <table class="min-w-full text-xs sm:text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600">
                        <th class="py-3 px-3 text-left font-semibold">Judul</th>
                        <th class="py-3 px-3 text-left font-semibold">Status</th>
                        <th class="py-3 px-3 text-left font-semibold">Terjual</th>
                        <th class="py-3 px-3 text-left font-semibold">Pendapatan</th>
                        <th class="py-3 px-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($concerts as $c)
                    <tr class="border-b hover:bg-gray-50">

                        <td class="px-3 py-4 font-medium">{{ $c->title }}</td>

                        <td class="px-3">
                            @php
                            $statusClass = [
                            'approved' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            'draft' => 'bg-gray-100 text-gray-700'
                            ][$c->approval_status ?? 'draft'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ ucfirst($c->approval_status ?? 'draft') }}
                            </span>

                            {{-- Tampilkan alasan jika di-reject --}}
                            @if($c->approval_status === 'rejected' && $c->notes)
                            <p class="text-xs text-red-500 mt-1">
                                ❌ Alasan: {{ $c->notes }}
                            </p>

                            @endif

                        </td>

                        <td class="px-3 text-indigo-600 font-semibold">{{ $c->ticketTypes->sum('sold') }}</td>

                        <td class="px-3 text-green-600 font-semibold">
                            Rp {{ number_format($c->ticketTypes->sum(fn($t)=>$t->price*$t->sold), 0, ',', '.') }}
                        </td>

                        {{-- ACTIONS --}}
                        {{-- ACTIONS --}}
                        <td class="px-3 py-3 text-center">
                            <div class="flex flex-wrap justify-center gap-2">

                                {{-- Jika REJECTED -> Semua disabled --}}
                                @if($c->approval_status === 'rejected')

                                <button class="px-3 py-1 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed" disabled>
                                    Edit
                                </button>

                                <button class="px-3 py-1 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed" disabled>
                                    Tiket
                                </button>

                                <button class="px-3 py-1 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed" disabled>
                                    Preview
                                </button>

                                <button class="px-3 py-1 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed" disabled>
                                    Hapus
                                </button>


                                {{-- Jika APPROVED -> Hanya Preview yang aktif --}}
                                @elseif($c->approval_status === 'approved')

                                <a href="{{ route('eo.concerts.edit', $c->id) }}"
                                    class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md">
                                    Edit
                                </a>

                                <a href="{{ route('eo.concerts.tickets.index', $c->id) }}"
                                    class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                                    Tiket
                                </a>

                                <a href="{{ route('concerts.show', $c->id) }}" target="_blank"
                                    class="px-3 py-1 bg-gray-700 hover:bg-gray-800 text-white rounded-md">
                                    Preview
                                </a>

                                <button onclick="confirmDelete({{ $c->id }}, '{{ $c->title }}', '{{ $c->approval_status }}')"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                    Hapus
                                </button>



                                {{-- DEFAULT (draft / pending) --}}
                                @else
                                <a href="{{ route('eo.concerts.edit', $c->id) }}"
                                    class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md">
                                    Edit
                                </a>

                                <a href="{{ route('eo.concerts.tickets.index', $c->id) }}"
                                    class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                                    Tiket
                                </a>

                                <a href="{{ route('concerts.show', $c->id) }}" target="_blank"
                                    class="px-3 py-1 bg-gray-700 hover:bg-gray-800 text-white rounded-md">
                                    Preview
                                </a>

                                <button onclick="confirmDelete({{ $c->id }}, '{{ $c->title }}', '{{ $c->approval_status }}')"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                    Hapus
                                </button>
                                @endif

                            </div>
                        </td>


                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-400">
                            Belum ada konser dibuat. Mulai dengan klik <b>“Buat Konser Baru”</b>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>


            </table>
        </div>

    </div>
</div>


{{-- DELETE MODAL --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white w-80 sm:w-96 p-6 rounded-lg shadow-xl text-center fade-in">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Hapus Konser?</h2>
        <p id="deleteMessage" class="text-sm text-gray-600 mb-6"></p>

        <div class="flex justify-center gap-3">
            <button onclick="closeDeleteModal()"
                class="px-4 py-2 bg-gray-300 rounded-lg text-sm hover:bg-gray-400">
                Batal
            </button>

            <form id="deleteForm" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>


{{-- Modal Script --}}
<script>
    function confirmDelete(id, title, status) {
        const modal = document.getElementById('deleteModal');
        const msg = document.getElementById('deleteMessage');
        const form = document.getElementById('deleteForm');

        if (status === 'approved') {
            msg.innerHTML = `Konser <b>${title}</b> sudah <span class='text-green-600'>disetujui</span> admin dan <b>tidak dapat dihapus</b>.`;
            form.classList.add('hidden');
        } else {
            msg.innerHTML = `Apakah yakin ingin menghapus <b>${title}</b>? Semua tiket juga akan terhapus!`;
            form.classList.remove('hidden');
            form.action = `/eo/concerts/${id}`;
        }

        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>


@endsection
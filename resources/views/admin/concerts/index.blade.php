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
                                <td class="px-4 py-2">{{ $concert->date ?? '—' }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $status = $concert->approval_status ?? $concert->status ?? 'unknown';
                                    @endphp
                                    @if($status === 'approved')
                                        <span class="text-green-600 font-medium">Disetujui</span>
                                    @elseif($status === 'rejected')
                                        <span class="text-red-600 font-medium">Ditolak</span>
                                    @else
                                        <span class="text-yellow-600 font-medium">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.concerts.show', $concert) }}"
                                       class="text-blue-600 hover:underline">
                                        History
                                    </a>
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
@endsection
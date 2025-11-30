@extends('layouts.eo')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Tiket untuk: {{ $concert->title }}</h2>

    <a href="{{ route('eo.concerts.tickets.create', $concert->id) }}"
        class="mb-3 inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg">
        + Tambah Tipe Tiket
    </a>

    {{-- SUBMIT APPROVAL SECTION --}}
    @if($tickets->count() > 0)
    <a href="{{ route('eo.concerts.review', $concert->id) }}"
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
        Review & Submit to Admin 
    </a>
    @else
    <button disabled
        class="bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed opacity-70">
        Tambahkan tiket dulu...
    </button>
    @endif



    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
        {{ session('success') }}
    </div>
    @endif

    <table class="w-full bg-white border shadow-sm rounded-lg">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Nama Tiket</th>
                <th class="p-3">Harga</th>
                <th class="p-3">Quota</th>
                <th class="p-3">Terjual</th>
                <th class="p-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $t)
            <tr class="border-t">
                <td class="p-3">{{ $t->name }}</td>
                <td class="p-3">Rp {{ number_format($t->price,0,',','.') }}</td>
                <td class="p-3">{{ $t->quota }}</td>
                <td class="p-3">{{ $t->sold }}</td>
                <td class="p-3 space-x-2 text-center">
                    <a href="{{ route('eo.tickets.edit', $t->id) }}"
                        class="bg-yellow-500 text-white px-3 py-1 rounded">
                        Edit
                    </a>

                    <form action="{{ route('eo.tickets.destroy', $t->id) }}"
                        method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-3 py-1 rounded"
                            onclick="return confirm('Hapus?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">
                    Belum ada tiket
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
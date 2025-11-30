@extends('layouts.eo')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white shadow rounded-lg">

    {{-- Tombol Back --}}
    <div class="mb-4">
        <a href="{{ route('eo.concerts.tickets.index', $ticket->concert_id) }}"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 text-sm font-medium">
            ← Kembali ke Daftar Tiket
        </a>
    </div>

    <h2 class="text-xl font-bold mb-4 text-gray-900">Edit Tipe Tiket 🎟️</h2>

    {{-- Success message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-3 py-2 rounded mb-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Edit --}}
    <form method="POST" action="{{ route('eo.tickets.update', $ticket->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="font-medium text-gray-700">Nama Tiket</label>
            <input type="text" name="name" required
                   value="{{ $ticket->name }}"
                   class="w-full p-2 border rounded focus:ring focus:ring-indigo-300">
        </div>

        <div class="mb-3">
            <label class="font-medium text-gray-700">Harga</label>
            <input type="number" name="price" required min="0"
                   value="{{ $ticket->price }}"
                   class="w-full p-2 border rounded focus:ring focus:ring-indigo-300">
        </div>

        <div class="mb-4">
            <label class="font-medium text-gray-700">Quota</label>
            <input type="number" name="quota" required min="1"
                   value="{{ $ticket->quota }}"
                   class="w-full p-2 border rounded focus:ring focus:ring-indigo-300">
        </div>

        <div class="flex justify-end">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow text-sm">
                💾 Update Tiket
            </button>
        </div>

    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 px-6 py-10">

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-200">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Konser 🎤</h2>

            @if($concert->status == 'draft')
            <form action="{{ route('eo.concerts.update', $concert->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="pending">
                <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg shadow">
                    Ajukan ke Admin 🚀
                </button>
            </form>
            @endif
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
            {{ session('success') }}
        </div>
        @endif

        {{-- EDIT FORM --}}
        <form action="{{ route('eo.concerts.update', $concert->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="font-semibold mb-1 block">Judul</label>
                <input type="text" name="title" value="{{ $concert->title }}"
                    class="w-full p-3 border border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="font-semibold mb-1 block">Lokasi</label>
                <input type="text" name="location" value="{{ $concert->location }}"
                    class="w-full p-3 border border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="font-semibold mb-1 block">Tanggal</label>
                <input type="date" name="date" value="{{ $concert->date }}"
                    class="w-full p-3 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium">Deskripsi Konser</label>
                <textarea name="description" rows="4"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-indigo-300">{{ $concert->description }}</textarea>
            </div>


            <div class="text-right">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        {{-- KELANDA TIKET --}}
        <div class="mt-10">
            <h3 class="text-lg font-semibold mb-3">Kelola Tipe Tiket 🎟️</h3>
            <a href="{{ route('eo.concerts.tickets.index', $concert->id) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                Kelola Tiket Sekarang ➜
            </a>
        </div>

    </div>

</div>
@endsection
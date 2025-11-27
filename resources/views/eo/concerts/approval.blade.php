@extends('layouts.eo')

@section('content')
<div class="min-h-screen flex justify-center items-center bg-gray-100 px-6 py-12">

    <div class="bg-white shadow-xl rounded-2xl p-8 max-w-lg w-full text-center border">

        <h2 class="text-2xl font-bold text-gray-800 mb-3">
            Ajukan Konser Ke Admin 🚀
        </h2>

        <p class="text-gray-600 mb-6">
            Pastikan semua data konser sudah benar sebelum diajukan ya!
        </p>

        <div class="mb-6 p-4 bg-gray-50 rounded-lg border text-left">
            <p><strong>Judul:</strong> {{ $concert->title }}</p>
            <p><strong>Lokasi:</strong> {{ $concert->location }}</p>
            <p><strong>Tanggal:</strong> {{ $concert->date }}</p>
            <p><strong>Deskripsi:</strong> {{ $concert->description ?? 'Tidak ada deskripsi' }}</p>
        </div>

        @if($concert->ticketTypes->count() > 0)

        <form action="{{ route('eo.concerts.submitApproval', $concert->id) }}" method="POST">
            @csrf
            <button
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold shadow transition">
                Ajukan ke Admin Sekarang ✔
            </button>
        </form>

        @else
        <div class="text-red-600 font-medium mb-4">
            *Tambahkan minimal 1 tipe tiket sebelum ajukan approval*
        </div>
        <a href="{{ route('eo.concerts.tickets.index', $concert->id) }}"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg shadow">
            Tambahkan Tipe Tiket 🎟️
        </a>
        @endif

        <a href="{{ route('eo.dashboard') }}"
            class="block mt-6 text-gray-500 hover:text-gray-700 underline">
            Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection

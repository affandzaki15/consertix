@extends('layouts.eo')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white shadow rounded-lg">
    <h2 class="text-xl font-bold mb-4">Tambah Tipe Tiket</h2>

    <form method="POST"
          action="{{ route('eo.concerts.tickets.store', $concert->id) }}">
        @csrf

        <div class="mb-3">
            <label class="font-medium">Nama Tiket</label>
            <input type="text" name="name" required
                   class="w-full p-2 border rounded">
        </div>

        <div class="mb-3">
            <label class="font-medium">Harga</label>
            <input type="number" name="price" required min="0"
                   class="w-full p-2 border rounded">
        </div>

        <div class="mb-3">
            <label class="font-medium">Quota</label>
            <input type="number" name="quota" required min="1"
                   class="w-full p-2 border rounded">
        </div>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
</div>
@endsection

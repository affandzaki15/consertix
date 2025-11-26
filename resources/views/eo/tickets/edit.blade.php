@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white shadow rounded-lg">
    <h2 class="text-xl font-bold mb-4">Edit Tipe Tiket</h2>

    <form method="POST"
          action="{{ route('eo.tickets.update', $ticket->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="font-medium">Nama Tiket</label>
            <input type="text" name="name" required
                   value="{{ $ticket->name }}"
                   class="w-full p-2 border rounded">
        </div>

        <div class="mb-3">
            <label class="font-medium">Harga</label>
            <input type="number" name="price" required min="0"
                   value="{{ $ticket->price }}"
                   class="w-full p-2 border rounded">
        </div>

        <div class="mb-3">
            <label class="font-medium">Quota</label>
            <input type="number" name="quota" required min="1"
                   value="{{ $ticket->quota }}"
                   class="w-full p-2 border rounded">
        </div>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Update
        </button>
    </form>
</div>
@endsection

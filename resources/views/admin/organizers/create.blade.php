@extends('layouts.app')

@section('content')
@include('admin.partials.menu')

<div class="max-w-xl mx-auto py-10 px-4">

    <h1 class="text-2xl font-bold mb-6">Tambah Organizer</h1>

    <form action="{{ route('admin.organizers.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">Nama</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
    </form>

</div>
@endsection

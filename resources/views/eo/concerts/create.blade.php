@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 px-6 py-10">

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-200">

        {{-- TITLE --}}
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Buat Konser Baru 🎤
        </h2>

        {{-- SUCCESS ALERT --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM --}}
       <form action="{{ route('eo.concerts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- JUDUL --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Judul Konser</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border border-gray-300 rounded-lg p-3 text-gray-800 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Masukkan nama konser" required>
                @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            {{-- LOKASI --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}"
                       class="w-full border border-gray-300 rounded-lg p-3 text-gray-800 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Contoh: Jakarta Convention Center" required>
                @error('location')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            {{-- TANGGAL --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Tanggal</label>
                <input type="date" name="date" value="{{ old('date') }}"
                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500"
                       required>
                @error('date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            {{-- GAMBAR --}}
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-1">Poster Konser</label>

                <input type="file" name="image_url" accept="image/*"
                    class="w-full bg-white border border-gray-300 rounded-lg p-3"
                    onchange="previewImage(event)" required>

                @error('image_url')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

                {{-- PREVIEW --}}
                <img id="preview"
                     class="mt-3 hidden w-40 rounded-lg shadow border border-gray-200"
                     alt="Preview">
            </div>

            {{-- SUBMIT BUTTON --}}
            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition">
                    Simpan & Tambahkan Tipe Tiket 🎟️
                </button>
            </div>

        </form>

    </div>

</div>

{{-- PREVIEW JAVASCRIPT --}}
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.classList.remove('hidden');
    preview.src = URL.createObjectURL(event.target.files[0]);
}
</script>

@endsection

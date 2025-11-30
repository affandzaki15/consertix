@extends('layouts.eo')

@section('content')
<div class="min-h-screen bg-gray-100 px-6 py-10">

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-md border border-gray-200">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $concert->title }}</h2>
                <p class="text-gray-500 text-sm mt-1">Perbarui informasi konser kamu ✨</p>
            </div>

            <div class="space-x-2">
                {{-- Tombol Ajukan Approval --}}
                @if($concert->approval_status == 'draft')
                <form action="{{ route('eo.concerts.update', $concert->id) }}" class="inline" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="approval_status" value="pending">

                </form>
                @endif

                {{-- Tombol Kelola Tiket --}}
                <a href="{{ route('eo.concerts.tickets.index', $concert->id) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow text-sm">
                    Kelola Tiket 🎟️
                </a>
            </div>
        </div>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif

        {{-- FORM EDIT --}}
        <form action="{{ route('eo.concerts.update', $concert->id) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- JUDUL --}}
            <div class="mb-5">
                <label class="font-semibold mb-2 block">Judul Konser</label>
                <input type="text" name="title" value="{{ old('title', $concert->title) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-300"
                    required>
            </div>

            {{-- LOKASI --}}
            <div class="mb-5">
                <label class="font-semibold mb-2 block">Lokasi</label>
                <input type="text" name="location" value="{{ old('location', $concert->location) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-300"
                    required>
            </div>

            {{-- TANGGAL --}}
            <div class="mb-5">
                <label class="font-semibold mb-2 block">Tanggal</label>
                <input type="date" name="date" value="{{ old('date', $concert->date) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-300">
            </div>

            {{-- WAKTU --}}
            <div class="mb-5">
                <label class="font-semibold mb-2 block">Waktu Mulai</label>
                <input type="time" name="time" value="{{ old('time', $concert->time) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-300">
            </div>

            {{-- POSTER --}}
            <div class="mb-6">
                <label class="font-semibold text-gray-700 mb-1 block">Poster Konser</label>

                @if($concert->image_url)
                <img src="{{ asset($concert->image_url) }}"
                    class="w-40 rounded-lg mb-3 shadow border">
                @endif

                <input type="file" name="image_url" accept="image/*"
                    class="w-full bg-white border border-gray-300 rounded-lg p-3"
                    onchange="previewImage(event)">
                <img id="preview" class="hidden w-40 rounded-lg shadow mt-3" alt="preview">
            </div>

            {{-- DESKRIPSI --}}
            <div class="mb-5">
                <label class="font-semibold mb-2 block">Deskripsi Konser</label>
                <textarea name="description" rows="4"
                    class="w-full border rounded-lg p-3 text-gray-700 focus:ring-2 focus:ring-indigo-300">{{ old('description', $concert->description) }}</textarea>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 px-6 py-3 shadow-md rounded-xl text-white font-semibold transition">
                    Simpan Perubahan 💾
                </button>
            </div>
        </form>


        {{-- STATUS INFO --}}
        <div class="mt-10 p-4 bg-gray-50 rounded-lg border text-sm text-gray-700">
            <strong>Status Konser:</strong>
            @if($concert->approval_status == 'approved')
            <span class="text-green-600 font-semibold">Approved ✔️</span>
            @elseif($concert->approval_status == 'pending')
            <span class="text-yellow-500 font-semibold">Pending Approval ⏳</span>
            @else
            <span class="text-red-500 font-semibold">Draft 🚧</span>
            @endif
        </div>

    </div>

</div>
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.classList.remove('hidden');
}
</script>
@endsection


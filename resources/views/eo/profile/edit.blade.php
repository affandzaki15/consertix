@extends('layouts.eo')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 mt-10 rounded-xl shadow-lg">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">Profil Organizer 🧑‍💼</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('eo.profile.update') }}" enctype="multipart/form-data">
        @csrf

        {{-- Nama EO --}}
        <div class="mb-4">
            <label class="font-semibold text-gray-800">Nama Organizer *</label>
            <input type="text" name="organization_name"
                   value="{{ old('organization_name', $organizer->organization_name ?? '') }}"
                   required
                   class="w-full p-3 border rounded-lg mt-1">
        </div>

        {{-- Email User (Readonly) --}}
        <div class="mb-4">
            <label class="font-semibold text-gray-800">Email</label>
            <input type="email"
                   value="{{ auth()->user()->email }}"
                   class="w-full p-3 bg-gray-100 border rounded-lg mt-1"
                   readonly>
        </div>

        {{-- Phone --}}
        <div class="mb-4">
            <label class="font-semibold text-gray-800">Telepon</label>
            <input type="text" name="phone"
                   value="{{ old('phone', $organizer->phone ?? '') }}"
                   class="w-full p-3 border rounded-lg mt-1">
        </div>

        {{-- Address --}}
        <div class="mb-4">
            <label class="font-semibold text-gray-800">Alamat</label>
            <textarea name="address" rows="3"
                      class="w-full p-3 border rounded-lg mt-1">{{ old('address', $organizer->address ?? '') }}</textarea>
        </div>

        {{-- Logo --}}
        <div class="mb-5">
            <label class="font-semibold text-gray-800">Logo Organizer</label>
            <input type="file" name="url_logo" class="w-full mt-2">

              @if(!empty($organizer->url_logo))
              <img src="{{ asset('foto/' . $organizer->url_logo) }}"
                  class="mt-3 w-24 h-24 object-cover rounded-lg shadow">
            @endif
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('eo.dashboard') }}"
               class="px-5 py-2 border border-gray-400 text-gray-700 rounded-lg">
                Kembali
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection

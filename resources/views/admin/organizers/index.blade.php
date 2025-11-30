@extends('layouts.app')

@section('content')
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Manajemen Organizer</h1>
                    <p class="text-gray-600 mt-1">Kelola akun organizer yang terdaftar di platform.</p>
                </div>
                <a href="{{ route('admin.organizers.create') }}"
                   class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm hover:shadow transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Organizer
                </a>
            </div>

            <!-- Daftar Organizer -->
            @if($organizers->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-800 mb-1">Belum Ada Organizer</h3>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Anda belum menambahkan organizer. Klik tombol di atas untuk mendaftarkan organizer baru.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($organizers as $user)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="p-5">
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-600 break-words">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Terdaftar: {{ $user->created_at->format('d M Y') }}
                                    </p>
                                </div>

                                <div class="flex space-x-2 pt-3 border-t border-gray-100">
                                    <a href="{{ route('admin.organizers.edit', $user) }}"
                                       class="flex-1 flex justify-center items-center px-3 py-2 text-sm font-medium text-yellow-700 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.organizers.destroy', $user) }}" method="POST" class="flex-1"
                                          onsubmit="return confirm('Yakin ingin menghapus organizer ini? Tindakan ini tidak bisa dikembalikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full flex justify-center items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($organizers->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $organizers->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
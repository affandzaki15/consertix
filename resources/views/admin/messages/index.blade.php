@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Pesan dari Pengguna</h1>
        <p class="text-gray-600 mt-1">Kelola pesan dan keluhan dari pengguna yang menghubungi Anda</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($messages->count() > 0)
        <!-- Messages Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Subjek/Pesan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($messages as $message)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-medium text-gray-900">{{ $message->name }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="mailto:{{ $message->email }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                                        {{ $message->email }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700 line-clamp-2">
                                        @if($message->subject)
                                            <strong>{{ $message->subject }}</strong><br>
                                        @endif
                                        {{ Str::limit($message->message, 60) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-600">
                                        {{ $message->created_at->format('d M Y H:i') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs rounded-lg font-medium transition"
                                                    onclick="return confirm('Yakin ingin menghapus pesan ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($messages->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M3 8v8a2 2 0 002 2h14a2 2 0 002-2V8m0 0L12 13m9-5H3" />
            </svg>
            <p class="text-gray-600 text-lg">Belum ada pesan dari pengguna</p>
        </div>
    @endif
</div>
@endsection

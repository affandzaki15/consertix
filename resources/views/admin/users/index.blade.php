@extends('layouts.app')

@section('content')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .user-row {
            opacity: 0;
            animation: fadeIn 0.4s ease-out forwards;
        }
        @for($i = 1; $i <= 20; $i++)
            .user-row:nth-child({{ $i }}) { animation-delay: {{ $i * 0.05 }}s; }
        @endfor
    </style>

    {{-- Include shared admin menu --}}
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">Manage Users</h1>
                    </div>
                    <p class="text-sm text-gray-600">Kelola pengguna, ubah peran, aktifkan/nonaktifkan, dan pantau aktivitas.</p>
                </div>

                <!-- Search Form -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                    <div class="relative">
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari nama atau email..."
                            class="w-full sm:w-64 border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                        />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition duration-200">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $index => $user)
                                <tr class="user-row hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $users->firstItem() + $index }}</td>
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $user->created_at?->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                                    <td class="px-5 py-4">
                                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="inline-flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:ring-1 focus:ring-indigo-300 focus:border-indigo-500">
                                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                                <option value="eo" {{ $user->role === 'eo' ? 'selected' : '' }}>EO</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-2.5 py-1 rounded text-xs font-medium transition">
                                                Simpan
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $isActive = isset($user->active) ? (bool)$user->active : true;
                                            $badgeClass = $isActive
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800';
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                            {{ $isActive ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition">Edit</a>

                                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" onsubmit="return confirm('Ubah status pengguna?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="active" value="{{ $isActive ? 0 : 1 }}">
                                                <button type="submit" class="text-xs px-2 py-1 rounded font-medium transition
                                                    {{ $isActive
                                                        ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'
                                                        : 'bg-blue-100 text-blue-800 hover:bg-blue-200' }}">
                                                    {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs px-2 py-1 rounded bg-red-100 text-red-800 hover:bg-red-200 font-medium transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-1.687 0-3.274-.396-4.688-1.109A8.003 8.003 0 014 12c0-1.105.292-2.17.819-3.125" />
                                            </svg>
                                            Tidak ada pengguna ditemukan.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
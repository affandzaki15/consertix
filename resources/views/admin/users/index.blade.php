@extends('layouts.app')

@section('content')
{{-- include shared admin menu --}}
@includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Manage Users</h1>
                <p class="mt-1 text-sm text-gray-600">Daftar pengguna, pencarian, sunting peran, aktif/non-aktif.</p>
            </div>
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / email..." class="border rounded px-3 py-2 text-sm" />
                <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded text-sm">Cari</button>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded border overflow-hidden">
            <table class="min-w-full divide-y text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-3">{{ $user->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->created_at?->format('d M Y') }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="border rounded px-2 py-1 text-sm">
                                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="eo" {{ $user->role === 'eo' ? 'selected' : '' }}>EO</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded text-xs">Simpan</button>
                                </form>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $isActive = isset($user->active) ? (bool)$user->active : true;
                                    $badge = $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ $isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:underline text-sm">Edit</a>

                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" onsubmit="return confirm('Ubah status pengguna?');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="active" value="{{ $isActive ? 0 : 1 }}">
                                        <button type="submit" class="text-sm px-2 py-1 rounded {{ $isActive ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $isActive ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm px-2 py-1 rounded bg-red-100 text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Tidak ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->withQueryString()->links() }}
        </div>

    </div>
</div>
@endsection
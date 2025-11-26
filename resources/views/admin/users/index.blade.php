@extends('layouts.app')

@section('content')
    {{-- pastikan menu tersedia jika layout tidak menampilkan --}}
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="mb-4">
        {{-- Back ke dashboard --}}
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 rounded-md text-sm hover:bg-gray-200">← Dashboard</a>
    </div>

    <div class="p-6">
        <h2 class="text-xl font-bold">Manage Users</h2>

        <form class="mt-4 flex space-x-2" method="GET" action="{{ route('admin.users.index') }}">
            <input name="q" value="{{ $q ?? '' }}" placeholder="Cari nama atau email" class="border rounded px-2 py-1" />
            <select name="role" class="border rounded px-2 py-1">
                <option value="">Semua role</option>
                <option value="user" {{ (request('role')=='user') ? 'selected' : '' }}>User</option>
                <option value="eo" {{ (request('role')=='eo') ? 'selected' : '' }}>EO</option>
                <option value="admin" {{ (request('role')=='admin') ? 'selected' : '' }}>Admin</option>
            </select>
            <button class="px-3 py-1 bg-indigo-600 text-white rounded">Filter</button>
        </form>

        <div class="mt-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Active</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr class="border-t">
                        <td class="py-2">{{ $u->id }}</td>
                        <td class="py-2">{{ $u->name }}</td>
                        <td class="py-2">{{ $u->email }}</td>
                        <td class="py-2">{{ $u->role }}</td>
                        <td class="py-2">{{ $u->active ? 'Yes' : 'No' }}</td>
                        <td class="py-2">
                            <a href="{{ route('admin.users.edit', $u) }}" class="text-indigo-600 mr-2">Edit</a>
                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
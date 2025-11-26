@extends('layouts.app')

@section('content')
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="mb-4">
        <a href="{{ url()->previous() ?? route('admin.users.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 rounded-md text-sm hover:bg-gray-200">← Kembali</a>
    </div>

    <div class="p-6 max-w-lg">
        <h2 class="text-xl font-bold">Edit User: {{ $user->name }}</h2>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-4">
            @csrf @method('PATCH')

            <label class="block">
                <span class="text-sm">Role</span>
                <select name="role" class="border rounded w-full px-2 py-1">
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </label>

            <label class="block mt-3">
                <span class="text-sm">Active</span>
                <input type="checkbox" name="active" value="1" {{ $user->active ? 'checked' : '' }}>
            </label>

            <div class="mt-4">
                <button class="px-3 py-1 bg-indigo-600 text-white rounded">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="ml-2 text-gray-600">Batal</a>
            </div>
        </form>
    </div>
@endsection
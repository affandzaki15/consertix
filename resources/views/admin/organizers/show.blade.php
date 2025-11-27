@extends('layouts.app')

@section('content')
{{-- include shared admin menu --}}
@includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold">Organizer Detail</h2>

        <div class="mt-4 bg-white p-4 border rounded">
            <p><strong>Nama:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $user->status ?? '-' }}</p>
            <hr class="my-3">
            <h3 class="font-semibold">Informasi Lengkap</h3>
            <pre class="text-xs bg-gray-100 p-2 rounded">{{ json_encode($user->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>

            <div class="mt-4 flex space-x-2">
                <form action="{{ route('admin.organizers.approve', $user) }}" method="POST">
                    @csrf
                    <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
                </form>

                <form action="{{ route('admin.organizers.reject', $user) }}" method="POST">
                    @csrf
                    <input type="text" name="note" placeholder="Catatan penolakan (opsional)" class="border rounded px-2 py-1 mr-2">
                    <button class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
                </form>

                <a href="{{ route('admin.organizers.pending') }}" class="px-3 py-1 bg-gray-200 rounded">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
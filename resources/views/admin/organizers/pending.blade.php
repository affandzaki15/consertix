
@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold">Pending Organizers</h2>

    <ul class="mt-4">
        @forelse($pending as $user)
            <li class="py-2 border-b flex items-center justify-between">
                <div>
                    <div class="font-medium">{{ $user->name }} <span class="text-sm text-gray-600">({{ $user->email }})</span></div>
                    <div class="text-xs text-gray-500">Dibuat: {{ $user->created_at ?? '-' }}</div>
                </div>

                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.organizers.show', $user) }}" class="px-3 py-1 bg-gray-200 rounded">Detail</a>

                    <form action="{{ route('admin.organizers.approve', $user) }}" method="POST">
                        @csrf
                        <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
                    </form>

                    <form action="{{ route('admin.organizers.reject', $user) }}" method="POST" class="flex items-center">
                        @csrf
                        <input type="text" name="note" placeholder="Catatan (opsional)" class="border rounded px-2 py-1 mr-2">
                        <button class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
                    </form>
                </div>
            </li>
        @empty
            <li>Tidak ada pending organizers.</li>
        @endforelse
    </ul>

    <div class="mt-4">
        {{ $pending->links() }}
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
{{-- include shared admin menu --}}
@includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h2 class="text-2xl font-bold mb-6">Pending Organizers</h2>

        <ul class="mt-4">
            @forelse($pending as $user)
                <li class="py-3 border-b flex items-center justify-between">
                    <div>
                        <div class="font-semibold">{{ $user->name }} 
                            <span class="text-sm text-gray-600">({{ $user->email }})</span>
                        </div>
                        <div class="text-xs text-gray-500">
                            Dibuat: {{ $user->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">

                        {{-- Detail --}}
                        <a href="{{ route('admin.organizers.show', $user) }}"
                            class="px-3 py-1 bg-gray-200 rounded">Detail</a>

                        {{-- Approve --}}
                        <form action="{{ route('admin.organizers.approve', $user) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
                        </form>

                        {{-- Reject --}}
                        <form action="{{ route('admin.organizers.reject', $user) }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="text" name="note" placeholder="Catatan (opsional)"
                                class="border rounded px-2 py-1 mr-2">
                            <button class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
                        </form>

                    </div>
                </li>
            @empty
                <li class="text-gray-500">Tidak ada organizer yang sedang menunggu approval.</li>
            @endforelse
        </ul>

        <div class="mt-4">
            {{ $pending->links() }}
        </div>

    </div>
</div>
@endsection

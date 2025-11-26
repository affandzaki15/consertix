@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold">Pending Organizers</h2>
    <ul class="mt-4">
        @foreach($pending as $user)
            <li class="py-2 border-b">
                {{ $user->name }} - {{ $user->email }}
                <a href="{{ route('admin.organizers.show', $user) }}" class="text-indigo-600 ml-4">Detail</a>
            </li>
        @endforeach
    </ul>
    {{ $pending->links() }}
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold">Tickets</h2>
    <ul class="mt-4">
        @foreach($tickets as $t)
            <li class="py-2 border-b">{{ $t->name }} — {{ $t->price ?? '-' }}</li>
        @endforeach
    </ul>
    {{ $tickets->links() }}
</div>
@endsection
@extends('layouts.app')

@section('content')
{{-- include shared admin menu --}}
@includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold">Tickets</h2>
        <ul class="mt-4">
            @foreach($tickets as $t)
                <li class="py-2 border-b">{{ $t->name }} — {{ $t->price ?? '-' }}</li>
            @endforeach
        </ul>
        {{ $tickets->links() }}
    </div>
</div>
@endsection
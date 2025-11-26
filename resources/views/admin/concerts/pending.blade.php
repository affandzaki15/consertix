
@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold">Pending Concerts</h2>
    <ul class="mt-4">
        @foreach($pending as $c)
            <li class="py-2 border-b">
                {{ $c->title }} — {{ $c->date ?? 'tanggal belum' }}
                <a href="{{ route('admin.concerts.show', $c) }}" class="text-indigo-600 ml-4">Inspect</a>
            </li>
        @endforeach
    </ul>
    {{ $pending->links() }}
</div>
@endsection
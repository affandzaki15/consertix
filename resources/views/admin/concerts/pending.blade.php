@extends('layouts.app')

@section('content')
{{-- include shared admin menu --}}
@includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold">Pending Concerts</h2>
        <ul class="mt-4">
            @foreach($pending as $c)
                <li class="py-2 border-b flex items-center justify-between">
                    <div>
                        {{ $c->title }} — {{ $c->date ?? 'tanggal belum' }}
                    </div>
                    <div class="flex items-center space-x-2">
                        <form action="{{ route('admin.concerts.approve', $c) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
                        </form>

                        <form action="{{ route('admin.concerts.reject', $c) }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="text" name="note" placeholder="Catatan (opsional)" class="border rounded px-2 py-1 mr-2">
                            <button class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
        {{ $pending->links() }}
    </div>
</div>
@endsection
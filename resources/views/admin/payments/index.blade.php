@extends('layouts.app')

@section('content')
{{-- include shared admin menu --}}
@includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold">Payments (proofs)</h2>
        <ul class="mt-4">
            @foreach($orders as $order)
                <li class="py-2 border-b">
                    #{{ $order->id }} — {{ $order->user->name ?? '–' }}
                    <a href="{{ route('admin.payments.show', $order) }}" class="text-indigo-600 ml-4">Lihat Bukti</a>
                </li>
            @endforeach
        </ul>
        {{ $orders->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold">Orders</h2>
    <ul class="mt-4">
        @foreach($orders as $order)
            <li class="py-2 border-b">
                #{{ $order->id }} — {{ $order->user->name ?? '–' }} — {{ $order->total_amount ?? '0' }}
                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 ml-4">Detail</a>
            </li>
        @endforeach
    </ul>
    {{ $orders->links() }}
</div>
@endsection
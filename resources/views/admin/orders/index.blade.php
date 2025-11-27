@extends('layouts.app')

@section('content')
{{-- include shared admin menu --}}
@includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold">Orders</h2>
        <table class="mt-4">
            <thead>
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Total Amount</th>
                    <th class="px-4 py-3">Payment Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $order->id }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $order->user_id ? ($usersMap[$order->user_id]->name ?? '-') : '-' }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($order->total_amount ?? 0,0,',','.') }}</td>
                        <td class="px-4 py-3">{{ $order->payment_status ?? $order->status ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @include('admin.orders.row-actions', ['order' => $order])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection
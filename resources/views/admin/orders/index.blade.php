@extends('layouts.app')

@section('content')
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900">Orders</h2>
            <div class="overflow-x-auto mt-4 shadow rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concert</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                             <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $order->id }}</td>
                                <td class="px-4 py-3">
                                    @if($order->concert)
                                        <span class="font-medium text-indigo-700">{{ $order->concert->title ?? $order->concert->name }}</span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($order->concert->date)->translatedFormat('d F Y') }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Concert deleted</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $order->buyer_name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $order->buyer_email ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $order->payment_method ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $status = $order->status ?? 'unknown';
                                        $badgeClass = match($status) {
                                            'completed' => 'bg-green-100 text-green-800',
                                            'processing' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-blue-100 text-blue-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        $displayStatus = match($status) {
                                            'completed' => 'Selesai',
                                            'processing' => 'Sedang Diproses',
                                            'paid' => 'Sudah Bayar',
                                            'failed' => 'Gagal',
                                            default => 'Unknown'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 inline-flex items-center rounded-full text-xs font-medium {{ $badgeClass }}">
                                        {{ $displayStatus }}
                                    </span>
                                </td>
                                                                <td class="px-4 py-3">
                                    @include('admin.orders.row-actions', ['order' => $order])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
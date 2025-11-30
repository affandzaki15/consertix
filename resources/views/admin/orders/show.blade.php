@extends('layouts.app')

@section('content')
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Orders</a>

            <h2 class="text-2xl font-bold mb-6">Order #{{ $order->id }}</h2>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div><strong>Reference Code:</strong> {{ $order->reference_code ?? '-' }}</div>
                    <div>
                        <strong>Payment Status:</strong>
                        <span class="px-2 py-1 rounded text-xs font-medium
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->payment_status ?? 'unknown') }}
                        </span>
                    </div>

                    <div><strong>Buyer Name:</strong> {{ $order->buyer_name ?? '-' }}</div>
                    <div><strong>Email:</strong> {{ $order->buyer_email ?? '-' }}</div>

                    <div><strong>Identity Type:</strong> {{ $order->identity_type ?? '-' }}</div>
                    <div><strong>Identity Number:</strong> {{ $order->identity_number ?? '-' }}</div>

                    <div><strong>Payment Method:</strong> {{ $order->payment_method ?? '-' }}</div>
                    <div><strong>Total Amount:</strong> Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</div>

                    <div><strong>Concert ID:</strong> {{ $order->concert_id ?? '-' }}</div>
                    <div><strong>Created At:</strong> {{ $order->created_at?->format('d M Y H:i') ?? '-' }}</div>

                    <div><strong>Tickets Generated:</strong> {{ $order->tickets_generated ? 'Yes' : 'No' }}</div>
                    @if($order->tickets_generated_at)
                        <div><strong>Generated At:</strong> {{ $order->tickets_generated_at->format('d M Y H:i') }}</div>
                    @endif
                </div>

                <div class="mt-6">
    <h3 class="font-semibold mb-2">Actions</h3>
    @if($order->status === 'paid' && !$order->tickets_generated)
        <form action="{{ route('admin.orders.generate-tickets', $order) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                onclick="return confirm('Generate tiket untuk order ini?')">
                Generate Tiket
            </button>
        </form>
    @elseif($order->tickets_generated)
        <span class="text-green-600 font-medium">✅ Tiket sudah digenerate</span>
    @else
        <span class="text-yellow-600">
            @if($order->status === 'processing')
                ⚠️ Menunggu pembayaran selesai
            @else
                ⚠️ Status: {{ ucfirst($order->status ?? 'unknown') }}
            @endif
        </span>
    @endif
</div>
            </div>
        </div>
    </div>
@endsection
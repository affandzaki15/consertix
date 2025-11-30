<a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline mr-3">
    View
</a>

@if(!($order->tickets_generated ?? false) && (!isset($order->status) || $order->status !== 'completed'))
    <form action="{{ route('admin.orders.generate-tickets', $order) }}" method="POST" class="inline">
        @csrf
        @method('POST')
        <button type="submit" class="text-green-600 hover:underline" onclick="return confirm('Generate tiket untuk order ini?')">
            Generate Tiket
        </button>
    </form>
@endif
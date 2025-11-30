@if($order->status === 'completed' && !$order->tickets_generated)
    <form action="{{ route('admin.orders.generate-tickets', $order) }}" method="POST" style="display:inline;" 
          onsubmit="return confirm('Generate tiket untuk order ini?')">
        @csrf
        <button type="submit" class="text-green-600 hover:underline text-sm">Generate Tiket</button>
    </form>
@elseif($order->tickets_generated)
    <span class="text-green-600 text-sm">✅ Tiket Sudah Ada</span>
@else
    <span class="text-yellow-600 text-sm">Status: {{ ucfirst($order->status ?? 'unknown') }}</span>
@endif
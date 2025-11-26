<div class="flex items-center space-x-2">
    <a href="{{ route('admin.orders.show', $order) }}" class="px-2 py-1 bg-gray-100 text-sm rounded hover:bg-gray-200">View</a>

    @if(Route::has('admin.payments.confirm'))
    <form action="{{ route('admin.payments.confirm', $order) }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="px-2 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">Confirm</button>
    </form>
    @endif

    @if(Route::has('admin.orders.generateTickets'))
    <form action="{{ route('admin.orders.generateTickets', $order) }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="px-2 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Generate</button>
    </form>
    @endif

    @if(Route::has('admin.payments.refund'))
    <form action="{{ route('admin.payments.refund', $order) }}" method="POST" class="inline" onsubmit="return confirm('Proses refund untuk order #{{ $order->id }}?')">
        @csrf
        <button type="submit" class="px-2 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">Refund</button>
    </form>
    @endif
</div>
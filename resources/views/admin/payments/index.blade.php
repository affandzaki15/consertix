@extends('layouts.app')

@section('content')
    {{-- Include shared admin menu --}}
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold">Payments (Order Verification)</h2>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Bayar</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $order->id }}</td>
                                <td class="px-4 py-3 text-sm font-mono text-gray-700">{{ $order->reference_code ?? '–' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $order->buyer_name ?? '–' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $order->buyer_email ?? '–' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @php
                                        $methodLabels = [
                                            'transfer_bank' => 'Transfer Bank',
                                            'bca' => 'BCA',
                                            'bni' => 'BNI',
                                            'bri' => 'BRI',
                                            'mandiri' => 'Mandiri',
                                            'gopay' => 'GoPay',
                                            'ovo' => 'OVO',
                                            'dana' => 'DANA',
                                            'shopeepay' => 'ShopeePay',
                                            'cash' => 'Cash on Delivery',
                                            'ewallet' => 'E-Wallet',
                                            'manual' => 'Manual Transfer',
                                        ];
                                    @endphp
                                    {{ $methodLabels[$order->payment_method] ?? $order->payment_method ?? '–' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $statusLabels = [
                                            'pending' => ['text' => 'Menunggu', 'bg' => 'bg-yellow-100', 'text-color' => 'text-yellow-800'],
                                            'paid' => ['text' => 'Lunas', 'bg' => 'bg-green-100', 'text-color' => 'text-green-800'],
                                            'confirmed' => ['text' => 'Dikonfirmasi', 'bg' => 'bg-green-100', 'text-color' => 'text-green-800'],
                                            'refunded' => ['text' => 'Direfund', 'bg' => 'bg-red-100', 'text-color' => 'text-red-800'],
                                            'cancelled' => ['text' => 'Dibatalkan', 'bg' => 'bg-gray-100', 'text-color' => 'text-gray-800'],
                                        ];
                                        $status = $statusLabels[$order->status] ?? ['text' => ucfirst($order->status ?? 'Unknown'), 'bg' => 'bg-gray-100', 'text-color' => 'text-gray-800'];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded {{ $status['bg'] }} {{ $status['text-color'] }}">
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $order->created_at?->format('d M Y H:i') ?? '–' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('admin.payments.show', $order) }}" class="text-indigo-600 hover:underline">
                                        Lihat Bukti
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                                    Tidak ada pembayaran yang perlu diverifikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
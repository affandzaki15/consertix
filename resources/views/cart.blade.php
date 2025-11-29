@extends('layouts.app')

@section('content')
<div class="w-full bg-gray-50 py-10 font-poppins">
    <div class="max-w-6xl mx-auto px-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($cartItems))
            <div class="bg-white rounded-2xl shadow p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto text-gray-400 mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437m0 0L6.75 12.75h10.5l2.25-6.75H5.106m0 0l-.383-1.438M6.75 12.75L7.5 15.75h9l.75-3" />
                </svg>
                <p class="text-gray-600 text-lg mb-6">Your cart is empty</p>
                <a href="{{ route('concerts.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-8">
                <!-- Cart Items -->
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        @php
                            // choose target: confirmation if order exists, otherwise create/resume order from cart
                            // pass ticketTypeId so clicking a specific cart item checks out just that ticket type
                            $targetUrl = $item['orderId'] ? route('purchase.confirmation', $item['orderId']) : route('cart.checkout', [$item['concertId'], $item['ticketTypeId']]);
                        @endphp

                        <a href="{{ $targetUrl }}" class="block">
                            <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between hover:shadow-lg transition">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $item['concert']->title }}</h3>
                                    <p class="text-gray-600 mb-2">{{ $item['ticketType']->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $item['quantity'] }} × Rp{{ number_format($item['price']) }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xl font-bold text-gray-900 mb-3">
                                        Rp{{ number_format($item['total']) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        <div class="flex justify-end mt-2 mb-4">
                            <form action="{{ route('cart.remove', $item['ticketTypeId']) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                    Remove
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary removed as requested -->
            </div>
        @endif
    </div>
</div>
@endsection

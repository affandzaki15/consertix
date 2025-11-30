@extends('layouts.app')

@section('title', $concert->title)

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Event Image -->
        <div class="lg:col-span-1">
            <div class="rounded-lg overflow-hidden shadow-md sticky top-8">
                <img src="{{ asset('storage/' . $concert->image_url) }}" alt="{{ $concert->title }}"
                    class="w-full h-96 object-cover">
            </div>
        </div>

        <!-- Right: Event Info -->
        <div class="lg:col-span-2">
            <!-- Title and Basic Info -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $concert->title }}</h1>

                <div class="space-y-3 text-gray-700 mb-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $concert->date->format('d F Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $concert->date->format('H:i') }} - 23:00</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $concert->location }}</span>
                    </div>
                </div>

                <!-- Creator Info -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <p class="text-sm text-gray-500 mb-3">Creator</p>
                    <a href="{{ route('organizers.show', $concert->organizer->id) }}" class="flex items-center gap-4 hover:opacity-75 transition-opacity">
                        @if($concert->organizer->url_logo)
                        <img src="{{ asset('storage/' . $concert->organizer->url_logo) }}" alt="{{ $concert->organizer->organization_name }}" class="w-16 h-16 object-contain rounded">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <span class="text-xs text-gray-500">No Logo</span>
                        </div>
                        @endif
                        <p class="font-semibold text-gray-900 text-lg">{{ $concert->organizer->organization_name ?? 'Unknown Organizer' }}</p>
                    </a>
                </div>

                <!-- Price Starts From -->
                <div class="mb-8">
                    <p class="text-sm text-gray-600 mb-2">Price starts from</p>
                    <p class="text-3xl font-bold text-indigo-600">Rp{{ number_format($concert->price, 0, ',', '.') }}</p>
                </div>

                <!-- Buy Ticket Button -->
                <div class="mb-8">
                    @if ($concert->approval_status !== 'approved')
                    <button disabled class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed">
                        Coming Soon
                    </button>
                    @elseif ($concert->selling_status === 'sold_out')
                    <button disabled class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed">
                        Sold Out
                    </button>
                    @elseif ($concert->selling_status === 'coming_soon')
                    <button disabled class="w-full bg-yellow-500 text-white py-3 rounded-lg font-semibold cursor-not-allowed">
                        Coming Soon
                    </button>
                    @else
                    @guest
                    <a href="{{ route('login') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-semibold text-center transition-colors">
                        Buy Ticket
                    </a>
                    @else
                    <a href="{{ route('purchase.show', $concert->id) }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-semibold text-center transition-colors">
                        Buy Ticket
                    </a>
                    @endguest
                    @endif
                </div>

                <!-- Description Section -->
                @if($concert->description)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Description</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $concert->description }}</p>
                </div>
                @endif

                <!-- Terms & Conditions -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Terms & Conditions</h2>

                    <div class="space-y-6 text-sm text-gray-700">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $concert->title }}</h3>
                            <p class="text-gray-600">{{ $concert->location }} | {{ $concert->date->format('F Y') }}</p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">GENERAL</h3>
                            <ul class="list-disc list-inside space-y-1 text-gray-700">
                                <li>Tickets are for personal use only - not for resale, giveaways, or business purposes.</li>
                                <li>Misused or resold tickets are invalid - no refund, no entry.</li>
                                <li>You are responsible for your own safety, health, and belongings. The promoter/artist/sponsors aren't liable for any loss or damage.</li>
                                <li>All sales are final - no refunds or exchanges unless the event is officially cancelled.</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">TICKETING</h3>
                            <ul class="list-disc list-inside space-y-1 text-gray-700">
                                <li>Tickets sold only via official promoter partners.</li>
                                <li>Prices exclude 10% government tax + platform fees.</li>
                                <li>Max 6 tickets per transaction on per category.</li>
                                <li>Ticket name must match a valid ID (ID/Passport/Driver's License).</li>
                                <li>Entry only allowed to the area matching your ticket category.</li>
                                <li>Cancellations: refunds follow promoter's policy (extra costs not covered).</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">WRISTBAND</h3>
                            <ul class="list-disc list-inside space-y-1 text-gray-700">
                                <li>E-vouchers must be exchanged for wristbands (details announced on promoter's social/website).</li>
                                <li>Redemption requires valid photo ID + official e-voucher.</li>
                                <li>If someone redeems on your behalf: printed e-voucher & copy of buyer's ID + signed authorization letter with Rp10,000 duty stamp.</li>
                                <li>Lost/damaged wristbands = no replacement.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
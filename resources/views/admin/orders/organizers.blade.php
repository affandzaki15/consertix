@extends('layouts.app')

@section('content')
    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900">Organizers</h2>
            <p class="text-sm text-gray-600 mt-2">Pilih organizer untuk melihat order yang terkait dengan konser-organizer tersebut.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                @forelse($organizers as $org)
                    <div class="block p-4 border rounded-lg hover:shadow-md hover:border-indigo-300">
                        <div class="flex items-start justify-between">
                            <div class="pr-4 flex-1">
                                <div class="text-lg font-semibold text-indigo-700">{{ $org->organization_name ?? 'Unnamed Organizer' }}</div>
                                <div class="text-sm text-gray-700 mt-1">Contact: {{ $org->user?->name ?? '-' }} &middot; <span class="text-gray-500">{{ $org->user?->email ?? '-' }}</span></div>
                                <div class="text-xs text-gray-500 mt-2">Registered: {{ $org->user?->created_at?->format('d M Y') ?? '-' }}</div>
                                <div class="mt-3 text-xs text-gray-600">Concerts: {{ $org->concerts->count() ?? 0 }}</div>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <div class="text-sm font-medium mb-2">{{ $org->orders_count ?? 0 }} Orders</div>

                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('admin.orders.by_organizer', $org->id) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">Lihat Orders</a>

                                    @if(!$org->user)
                                        <span class="text-xs text-red-600">No user</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-gray-500">No organizers found.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

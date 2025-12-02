@extends('layouts.eo')

@section('title', 'EO - Manage Vouchers')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Voucher</h1>
                <p class="text-gray-600 mt-2">Buat dan kelola kode diskon untuk acara Anda</p>
            </div>
            <a href="{{ route('eo.vouchers.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                + Buat Voucher Baru
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($vouchers->count() > 0)
            <div class="grid gap-6">
                @foreach ($vouchers as $voucher)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $voucher->code }}</h3>
                                    @if ($voucher->is_active)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">Aktif</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded">Nonaktif</span>
                                    @endif
                                </div>
                                @if ($voucher->description)
                                    <p class="text-gray-600 text-sm mt-1">{{ $voucher->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('eo.vouchers.edit', $voucher->id) }}" class="text-indigo-600 hover:text-indigo-700">Edit</a>
                                <form action="{{ route('eo.vouchers.destroy', $voucher->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus voucher ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                            <div>
                                <p class="text-gray-600 text-sm">Jenis Diskon</p>
                                <p class="font-medium">
                                    @if ($voucher->discount_type === 'percentage')
                                        {{ $voucher->discount_value }}%
                                    @else
                                        Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Penggunaan</p>
                                <p class="font-medium">{{ $voucher->usage_count }} / @if ($voucher->usage_limit) {{ $voucher->usage_limit }} @else Unlimited @endif</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Max per User</p>
                                <p class="font-medium">{{ $voucher->max_per_user }}x</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Berlaku Hingga</p>
                                <p class="font-medium">
                                    @if ($voucher->valid_until)
                                        {{ $voucher->valid_until->format('d M Y') }}
                                    @else
                                        Selamanya
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <a href="{{ route('eo.vouchers.stats', $voucher->id) }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                                Lihat Statistik Penggunaan →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $vouchers->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-600 mb-4">Belum ada voucher. Buat voucher pertama Anda sekarang!</p>
                <a href="{{ route('eo.vouchers.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                    Buat Voucher Baru
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

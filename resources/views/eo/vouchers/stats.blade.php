@extends('layouts.app')

@section('title', 'Statistik Voucher: ' . $voucher->code)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('eo.vouchers.index') }}" class="text-indigo-600 hover:text-indigo-700">← Kembali ke Daftar Voucher</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Statistik Voucher: {{ $voucher->code }}</h1>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Penggunaan</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsages }}</p>
                @if ($remainingUsages !== null)
                    <p class="text-xs text-gray-500 mt-2">Sisa: {{ $remainingUsages }}</p>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Jenis Diskon</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">
                    @if ($voucher->discount_type === 'percentage')
                        {{ $voucher->discount_value }}%
                    @else
                        Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                    @endif
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Status</p>
                <div class="mt-2">
                    @if ($voucher->is_active)
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded">Aktif</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1 rounded">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Riwayat Penggunaan -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Riwayat Penggunaan</h2>
            </div>

            @if ($usages->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Digunakan Pada</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($usages as $usage)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $usage->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $usage->user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if ($usage->used_at)
                                            {{ $usage->used_at->format('d M Y H:i') }}
                                        @else
                                            Pending
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-6 border-t border-gray-200">
                    {{ $usages->links() }}
                </div>
            @else
                <div class="p-6 text-center text-gray-600">
                    Belum ada penggunaan voucher ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

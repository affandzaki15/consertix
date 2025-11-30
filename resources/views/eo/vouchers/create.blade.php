@extends('layouts.app')

@section('title', 'Buat Voucher Baru')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('eo.vouchers.index') }}" class="text-indigo-600 hover:text-indigo-700">← Kembali ke Daftar Voucher</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Buat Voucher Baru</h1>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('eo.vouchers.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Kode Voucher -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Kode Voucher</label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" 
                           placeholder="Contoh: SAVE50" maxlength="50"
                           class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                    <textarea id="description" name="description" rows="3" 
                              placeholder="Contoh: Diskon khusus untuk pembeli setia"
                              class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis dan Nilai Diskon -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="discount_type" class="block text-sm font-medium text-gray-700">Jenis Diskon</label>
                        <select id="discount_type" name="discount_type" 
                                class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('discount_type') border-red-500 @enderror">
                            <option value="">Pilih Jenis</option>
                            <option value="percentage" @selected(old('discount_type') === 'percentage')>Persentase (%)</option>
                            <option value="fixed" @selected(old('discount_type') === 'fixed')>Jumlah Tetap (Rp)</option>
                        </select>
                        @error('discount_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="discount_value" class="block text-sm font-medium text-gray-700">Nilai Diskon</label>
                        <input type="number" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" 
                               placeholder="Contoh: 50"
                               class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('discount_value') border-red-500 @enderror">
                        @error('discount_value')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Batas Penggunaan -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="usage_limit" class="block text-sm font-medium text-gray-700">Batas Total Penggunaan (Opsional)</label>
                        <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" 
                               placeholder="Kosongkan untuk unlimited"
                               class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('usage_limit') border-red-500 @enderror">
                        @error('usage_limit')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_per_user" class="block text-sm font-medium text-gray-700">Max Penggunaan per User</label>
                        <input type="number" id="max_per_user" name="max_per_user" value="{{ old('max_per_user', 1) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('max_per_user') border-red-500 @enderror">
                        @error('max_per_user')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal Berlaku -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="valid_from" class="block text-sm font-medium text-gray-700">Berlaku Dari (Opsional)</label>
                        <input type="date" id="valid_from" name="valid_from" value="{{ old('valid_from') }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('valid_from') border-red-500 @enderror">
                        @error('valid_from')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="valid_until" class="block text-sm font-medium text-gray-700">Berlaku Hingga (Opsional)</label>
                        <input type="date" id="valid_until" name="valid_until" value="{{ old('valid_until') }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('valid_until') border-red-500 @enderror">
                        @error('valid_until')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', true)) 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Voucher aktif sekarang</label>
                </div>

                <!-- Tombol -->
                <div class="flex gap-3 pt-6">
                    <a href="{{ route('eo.vouchers.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Buat Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

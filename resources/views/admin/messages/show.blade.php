@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.contact-messages.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Detail Pesan</h1>
        </div>
        <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition"
                    onclick="return confirm('Yakin ingin menghapus pesan ini?')">
                <i class="fa-solid fa-trash mr-2"></i> Hapus
            </button>
        </form>
    </div>

    <!-- Message Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <!-- Header Info -->
        <div class="mb-8 pb-6 border-b border-gray-200">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $message->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Email</p>
                    <a href="mailto:{{ $message->email }}" class="text-lg font-semibold text-indigo-600 hover:text-indigo-800">
                        {{ $message->email }}
                    </a>
                </div>
            </div>
            
            <div class="mt-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tanggal Dikirim</p>
                <p class="text-gray-700">{{ $message->created_at->format('d F Y H:i:s') }}</p>
            </div>
        </div>

        <!-- Subject -->
        @if($message->subject)
            <div class="mb-6">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Subjek</p>
                <p class="text-lg font-semibold text-gray-900">{{ $message->subject }}</p>
            </div>
        @endif

        <!-- Message Content -->
        <div class="mb-6">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Pesan</p>
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 pt-6 border-t border-gray-200">
            <a href="mailto:{{ $message->email }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                <i class="fa-solid fa-reply mr-2"></i> Balas Email
            </a>
            <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/', '', $message->email), '0') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                <i class="fa-brands fa-whatsapp mr-2"></i> Chat WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection

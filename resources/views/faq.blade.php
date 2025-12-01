<!-- FAQ Page for Concertix -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white py-16">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-4xl font-bold mb-8 text-center text-gray-900">Frequently Asked Questions</h1>
        <div class="space-y-4">
            @php
                $faqs = [
                    [
                        'q' => 'Cara Membeli Tiket di Concertix',
                        'a' => 'Pilih konser, tentukan jadwal, pilih tiket, dan lakukan pembayaran. Tiket akan dikirim ke email setelah pembayaran berhasil.'
                    ],
                    [
                        'q' => 'Informasi Email Konfirmasi',
                        'a' => 'Email konfirmasi dan e-ticket akan dikirim otomatis setelah pembayaran berhasil. Pastikan email yang dimasukkan benar.'
                    ],
                    [
                        'q' => 'Kebijakan Pembatalan dan Pengembalian Dana',
                        'a' => 'Kebijakan pembatalan dan refund mengikuti aturan promotor. Silakan cek detail event atau hubungi CS.'
                    ],
                    [
                        'q' => 'Batas Waktu Pembayaran',
                        'a' => 'Waktu pembayaran maksimal 15 menit setelah checkout. Jika lewat, pesanan otomatis dibatalkan.'
                    ],
                    [
                        'q' => 'Penukaran E-Tiket',
                        'a' => 'E-ticket dapat langsung digunakan untuk masuk ke venue. Tunjukkan QR code ke petugas.'
                    ],
                    [
                        'q' => 'Metode Pembayaran yang Tersedia',
                        'a' => 'Kami mendukung transfer bank, e-wallet, dan kartu kredit.'
                    ],
                    [
                        'q' => 'Informasi Harga Tiket',
                        'a' => 'Harga tiket tertera di halaman event. Harga dapat berubah sesuai kebijakan promotor.'
                    ],
                    [
                        'q' => 'Data Pemesan untuk Pembelian Lebih dari Satu Tiket',
                        'a' => 'Setiap tiket dapat diisi data pemilik berbeda saat checkout.'
                    ],
                    [
                        'q' => 'Informasi Seputar Acara',
                        'a' => 'Detail acara, lokasi, dan waktu tersedia di halaman event.'
                    ],
                ];
            @endphp
            @foreach($faqs as $i => $faq)
            <div class="border-b">
                <button type="button" class="w-full flex justify-between items-center py-4 text-left font-semibold text-gray-900 focus:outline-none" onclick="toggleFaq({{ $i }})">
                    {{ $faq['q'] }}
                    <i id="icon-{{ $i }}" class="fa-solid fa-chevron-down text-gray-600"></i>
                </button>
                <div id="faq-{{ $i }}" class="hidden pb-4 text-gray-700">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    function toggleFaq(id) {
        const content = document.getElementById('faq-' + id);
        const icon = document.getElementById('icon-' + id);
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        } else {
            content.classList.add('hidden');
            if (icon.classList.contains('fa-chevron-up')) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    }
</script>

@include('partials.footer')
@endsection

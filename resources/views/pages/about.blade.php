@extends('layouts.app')

@section('title', 'About - Artatix')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-[#0d0f55] to-[#0a0c38] text-white py-20 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Tentang Artatix</h1>
            <p class="text-lg md:text-xl text-gray-300">Platform Manajemen Tiket Pertunjukan Profesional</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="max-w-6xl mx-auto px-4 py-16">
        <!-- About Overview -->
        <div class="grid md:grid-cols-2 gap-12 mb-20 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Apa itu Artatix?</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Artatix merupakan platform penyedia Ticket Management Service (TMS) untuk mendukung setiap acara apaupun tempat wisata selanjutnya membutuhkan sistem manajemen tiket, dan memberikan kemudahan kepada pelanggan dalam melakukan pemesanan tiket, penjualan tiket secara mandiri, maupun administrasi tiket.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Artatix memiliki tujuan bahwa teknologi unggulan yang kami miliki dapat memudahkan, mengutamakan dan mempermudah pelayanan penyediaan tiket di berbagai tempat wisata selanjutnya mendatangkan laporan pra-acara hingga menyediakan laporan tiket acara secara mandiri.
                </p>
            </div>
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-xl p-8 text-white">
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-white text-indigo-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Cepat & Efisien</h3>
                            <p class="text-indigo-100 text-sm">Proses pemesanan tiket yang mudah dan cepat</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-white text-indigo-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Aman & Terpercaya</h3>
                            <p class="text-indigo-100 text-sm">Sistem keamanan tingkat enterprise</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-white text-indigo-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Fitur Lengkap</h3>
                            <p class="text-indigo-100 text-sm">Dashboard dan laporan komprehensif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid md:grid-cols-4 gap-8 mb-20">
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="text-4xl font-bold text-indigo-600 mb-2">2.047.987++</div>
                <p class="text-gray-600 font-medium">Tiket Terjual</p>
            </div>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="text-4xl font-bold text-indigo-600 mb-2">987++</div>
                <p class="text-gray-600 font-medium">Event</p>
            </div>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="text-4xl font-bold text-indigo-600 mb-2">87++</div>
                <p class="text-gray-600 font-medium">Kota</p>
            </div>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="text-4xl font-bold text-indigo-600 mb-2">100%</div>
                <p class="text-gray-600 font-medium">Kepuasan Pelanggan</p>
            </div>
        </div>

        <!-- Vision & Mission -->
        <div class="grid md:grid-cols-2 gap-12 mb-20">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-100 text-indigo-600 mr-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </span>
                    Visi Kami
                </h3>
                <p class="text-gray-700 leading-relaxed">
                    Menjadi platform manajemen tiket terdepan di Indonesia yang mempermudah akses ke berbagai acara dan pertunjukan berkualitas tinggi, serta memberikan pengalaman terbaik kepada setiap pengguna.
                </p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-100 text-indigo-600 mr-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </span>
                    Misi Kami
                </h3>
                <p class="text-gray-700 leading-relaxed">
                    Menyediakan solusi teknologi tiket yang inovatif, aman, dan mudah digunakan untuk meningkatkan efisiensi pengelolaan acara dan memberikan kemudahan bagi pengunjung dalam mengakses tiket pertunjukan.
                </p>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white rounded-lg shadow-lg p-12 mb-20">
            <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Fitur Unggulan</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-indigo-100 rounded-lg p-6 mb-4 inline-block">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Tiket Fleksibel</h3>
                    <p class="text-gray-600">Kelola berbagai jenis tiket dengan harga dan kuota yang dapat disesuaikan</p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 rounded-lg p-6 mb-4 inline-block">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan & Analytics</h3>
                    <p class="text-gray-600">Dapatkan insights mendalam tentang penjualan dan performa acara Anda</p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 rounded-lg p-6 mb-4 inline-block">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Multi Metode Pembayaran</h3>
                    <p class="text-gray-600">Dukung berbagai metode pembayaran untuk kemudahan pelanggan</p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 rounded-lg p-6 mb-4 inline-block">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">E-Ticket Digital</h3>
                    <p class="text-gray-600">Kirim tiket digital langsung ke pelanggan via email atau SMS</p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 rounded-lg p-6 mb-4 inline-block">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sistem Keamanan Canggih</h3>
                    <p class="text-gray-600">Enkripsi tingkat enterprise melindungi data pelanggan Anda</p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 rounded-lg p-6 mb-4 inline-block">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Support 24/7</h3>
                    <p class="text-gray-600">Tim dukungan kami siap membantu Anda kapan saja</p>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="bg-indigo-50 rounded-lg p-12 mb-20">
            <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Perjalanan Kami</h2>
            <div class="space-y-8">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white">
                            <span class="text-lg font-semibold">2021</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Artatix Didirikan</h3>
                        <p class="text-gray-700 mt-2">Artatix berhasil diluncurkan dengan suatu rasalnya dengan jutaan ribu pembeli yang tersebar di seluruh Indonesia.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white">
                            <span class="text-lg font-semibold">2024</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Ekspansi Nasional</h3>
                        <p class="text-gray-700 mt-2">Kami terus berkembang dan hadir di lebih dari 87 kota di seluruh Indonesia dengan fitur-fitur terbaru.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white">
                            <span class="text-lg font-semibold">2025</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Inovasi Berkelanjutan</h3>
                        <p class="text-gray-700 mt-2">Komitmen kami untuk terus berinovasi menghadirkan pengalaman terbaik bagi pengguna di seluruh Indonesia.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-lg p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Siap Memulai?</h2>
            <p class="text-indigo-100 mb-8 text-lg">Bergabunglah dengan ribuan penyelenggara acara yang mempercayai Artatix</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('concerts.index') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Jelajahi Konser
                </a>
                <a href="{{ route('register') }}" class="bg-indigo-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition border border-white">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <section class="bg-gray-900 text-gray-400 py-12 mt-20">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p>&copy; 2025 Artatix. Semua hak dilindungi. Platform Tiket Management Service Terpercaya di Indonesia.</p>
        </div>
    </section>
</div>
@endsection

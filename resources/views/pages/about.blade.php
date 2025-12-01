@extends('layouts.app')

@section('title', 'About - Concertix')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-[#0d0f55] to-[#0a0c38] text-white py-20 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Tentang Concertix</h1>
            <p class="text-lg md:text-xl text-gray-300">Platform Manajemen Tiket Pertunjukan Profesional</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="max-w-6xl mx-auto px-4 py-16">
        <!-- About Overview -->
        <div class="grid md:grid-cols-2 gap-12 mb-20 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Apa itu Concertix?</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Concertix merupakan platform penyedia Ticket Management Service (TMS) untuk mendukung setiap acara apapun tempat wisata selanjutnya membutuhkan sistem manajemen tiket, dan memberikan kemudahan kepada pelanggan dalam melakukan pemesanan tiket, penjualan tiket secara mandiri, maupun administrasi tiket.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Concertix memiliki tujuan bahwa teknologi unggulan yang kami miliki dapat memudahkan, mengutamakan dan mempermudah pelayanan penyediaan tiket di berbagai tempat wisata selanjutnya mendatangkan laporan pra-acara hingga menyediakan laporan tiket acara secara mandiri.
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
                        <h3 class="text-lg font-semibold text-gray-900">Concertix Didirikan</h3>
                        <p class="text-gray-700 mt-2">Concertix berhasil diluncurkan dengan suatu rasalnya dengan jutaan ribu pembeli yang tersebar di seluruh Indonesia.</p>
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
            <p class="text-indigo-100 mb-8 text-lg">Bergabunglah dengan ribuan penyelenggara acara yang mempercayai Concertix</p>
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

    @include('partials.footer')

</div>
@endsection
